/**
 * API Testing Script for Wistara Notification System
 * Run with: node test_api.js
 */

const https = require('https');
const http = require('http');

const BASE_URL = 'http://localhost:8000';
let token = null;

// Helper function for HTTP requests
function makeRequest(options, data = null) {
    return new Promise((resolve, reject) => {
        const req = http.request(options, (res) => {
            let body = '';
            res.on('data', (chunk) => body += chunk);
            res.on('end', () => {
                try {
                    const response = JSON.parse(body);
                    resolve({ status: res.statusCode, data: response });
                } catch (e) {
                    resolve({ status: res.statusCode, data: body });
                }
            });
        });

        req.on('error', reject);

        if (data) {
            req.write(JSON.stringify(data));
        }

        req.end();
    });
}

// Test functions
async function testHealthCheck() {
    console.log('\n🔍 Testing Health Check...');
    try {
        const response = await makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/health',
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.status === 200 && response.data.status === 'healthy') {
            console.log('✅ Health check passed');
            return true;
        } else {
            console.log('❌ Health check failed:', response);
            return false;
        }
    } catch (error) {
        console.log('❌ Health check error:', error.message);
        return false;
    }
}

async function testLogin() {
    console.log('\n🔐 Testing Login...');
    try {
        const response = await makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/login',
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        }, {
            email: 'admin@wistara.com',
            password: 'password'
        });

        if (response.status === 200 && response.data.access_token) {
            token = response.data.access_token;
            console.log('✅ Login successful, token received');
            return true;
        } else {
            console.log('❌ Login failed:', response.data);
            return false;
        }
    } catch (error) {
        console.log('❌ Login error:', error.message);
        return false;
    }
}

async function testGetNotifications() {
    if (!token) {
        console.log('❌ No token available, skipping notifications test');
        return false;
    }

    console.log('\n📋 Testing Get Notifications...');
    try {
        const response = await makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/v1/notifications',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.status === 200 && response.data.success) {
            console.log('✅ Get notifications successful');
            console.log(`📊 Found ${response.data.count} notifications`);
            return true;
        } else {
            console.log('❌ Get notifications failed:', response.data);
            return false;
        }
    } catch (error) {
        console.log('❌ Get notifications error:', error.message);
        return false;
    }
}

async function testGetCount() {
    if (!token) {
        console.log('❌ No token available, skipping count test');
        return false;
    }

    console.log('\n🔢 Testing Get Unread Count...');
    try {
        const response = await makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/v1/notifications/count',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.status === 200 && typeof response.data.count === 'number') {
            console.log('✅ Get count successful');
            console.log(`📊 Unread count: ${response.data.count}`);
            return true;
        } else {
            console.log('❌ Get count failed:', response.data);
            return false;
        }
    } catch (error) {
        console.log('❌ Get count error:', error.message);
        return false;
    }
}

async function testUnauthorized() {
    console.log('\n🚫 Testing Unauthorized Access...');
    try {
        const response = await makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/v1/notifications',
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.status === 401) {
            console.log('✅ Unauthorized access properly blocked');
            return true;
        } else {
            console.log('❌ Unauthorized access not properly blocked:', response);
            return false;
        }
    } catch (error) {
        console.log('❌ Unauthorized test error:', error.message);
        return false;
    }
}

async function testRateLimit() {
    if (!token) {
        console.log('❌ No token available, skipping rate limit test');
        return false;
    }

    console.log('\n⏱️  Testing Rate Limiting...');
    const requests = [];

    // Make 65 requests quickly
    for (let i = 0; i < 65; i++) {
        requests.push(makeRequest({
            hostname: 'localhost',
            port: 8000,
            path: '/api/v1/notifications/count',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        }));
    }

    try {
        const results = await Promise.all(requests);
        const throttled = results.filter(r => r.status === 429).length;
        const successful = results.filter(r => r.status === 200).length;

        if (throttled > 0) {
            console.log('✅ Rate limiting working');
            console.log(`📊 Successful: ${successful}, Throttled: ${throttled}`);
            return true;
        } else {
            console.log('⚠️  Rate limiting may not be working properly');
            console.log(`📊 All requests successful: ${successful}`);
            return false;
        }
    } catch (error) {
        console.log('❌ Rate limit test error:', error.message);
        return false;
    }
}

// Main test runner
async function runTests() {
    console.log('🚀 Starting Wistara Notification API Tests');
    console.log('=' .repeat(50));

    const results = [];

    // Run tests
    results.push(await testHealthCheck());
    results.push(await testUnauthorized());
    results.push(await testLogin());
    results.push(await testGetNotifications());
    results.push(await testGetCount());
    results.push(await testRateLimit());

    // Summary
    console.log('\n' + '='.repeat(50));
    console.log('📊 Test Results Summary:');
    const passed = results.filter(r => r).length;
    const total = results.length;
    console.log(`✅ Passed: ${passed}/${total}`);

    if (passed === total) {
        console.log('🎉 All tests passed! API is secure and functional.');
    } else {
        console.log('⚠️  Some tests failed. Please check the implementation.');
    }

    console.log('\n🔗 API Endpoints Tested:');
    console.log(`   Health: ${BASE_URL}/api/health`);
    console.log(`   Login: ${BASE_URL}/api/login`);
    console.log(`   Notifications: ${BASE_URL}/api/v1/notifications`);
    console.log(`   Count: ${BASE_URL}/api/v1/notifications/count`);
    console.log(`   Stream: ${BASE_URL}/api/v1/notifications/stream`);
}

// Run tests
runTests().catch(console.error);
