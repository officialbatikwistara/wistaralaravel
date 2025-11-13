<!-- resources/views/components/notification.blade.php -->
<style>
    .notification-panel {
        position: relative;
        display: inline-block;
    }

    .notification-button {
        position: relative;
        padding: 0.5rem;
        background: none;
        border: none;
        cursor: pointer;
    }

    .notification-button:hover {
        color: #4B5563;
    }

    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(50%, -50%);
        background: #EF4444;
        color: white;
        border-radius: 9999px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .notification-dropdown {
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        width: 20rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }

    .notification-header {
        padding: 1rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-list {
        max-height: 24rem;
        overflow-y: auto;
    }

    .notification-item {
        padding: 1rem;
        border-bottom: 1px solid #E5E7EB;
        cursor: pointer;
    }

    .notification-item:hover {
        background: #F3F4F6;
    }

    .notification-item.unread {
        background: #EFF6FF;
    }

    .notification-icon {
        width: 1.5rem;
        height: 1.5rem;
        margin-right: 1rem;
    }

    .notification-content {
        margin-left: 2.5rem;
    }

    .mark-read-button {
        color: #2563EB;
        font-size: 0.875rem;
        background: none;
        border: none;
        cursor: pointer;
    }

    .mark-read-button:hover {
        color: #1D4ED8;
    }

    /* Toast Notification Styles */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        padding: 16px;
        max-width: 350px;
        z-index: 1000;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
        border-left: 4px solid #3B82F6;
    }

    .toast-notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-notification.success {
        border-left-color: #10B981;
    }

    .toast-notification.error {
        border-left-color: #EF4444;
    }

    .toast-notification.warning {
        border-left-color: #F59E0B;
    }

    .toast-notification.info {
        border-left-color: #3B82F6;
    }

    .toast-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .toast-title {
        font-weight: 600;
        font-size: 14px;
        color: #1F2937;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #6B7280;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-body {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.4;
    }
</style>

@php
    $unreadCount = $unreadCount ?? 0;
    $notifications = $notifications ?? collect();
@endphp

<div class="notification-panel">
    <button onclick="toggleNotifications()" class="notification-button">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="notification-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    <div id="notificationDropdown" class="notification-dropdown" style="display: none;">
        <div class="notification-header">
            <h3 style="font-size: 1.125rem; font-weight: 600;">Notifikasi</h3>
            @if($unreadCount > 0)
                <button onclick="markAllAsRead()" class="mark-read-button">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <div class="notification-list">
            <div style="text-align: center; padding: 2rem; color: #6B7280;">
                Loading notifications...
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer"></div>

<script>
let isDropdownOpen = false;
let lastNotificationCount = 0;

function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    isDropdownOpen = !isDropdownOpen;
    dropdown.style.display = isDropdownOpen ? 'block' : 'none';

    if (isDropdownOpen) {
        loadNotifications();
    }
}

function loadNotifications() {
    fetch('/notifications')
        .then(response => response.json())
        .then(data => {
            const list = document.querySelector('.notification-list');
            list.innerHTML = '';

            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach(notification => {
                    const item = createNotificationItem(notification);
                    list.appendChild(item);
                });
            } else {
                list.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6B7280;">Tidak ada notifikasi</div>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            const list = document.querySelector('.notification-list');
            list.innerHTML = '<div style="text-align: center; padding: 2rem; color: #EF4444;">Error loading notifications</div>';
        });
}

function createNotificationItem(notification) {
    const item = document.createElement('div');
    item.className = `notification-item ${!notification.read_at ? 'unread' : ''}`;
    item.onclick = () => handleNotificationClick(notification.id, notification.data.url || '#');

    const iconHtml = getNotificationIcon(notification);

    item.innerHTML = `
        <div style="display: flex; align-items: start;">
            ${iconHtml}
            <div class="notification-content">
                <p style="font-weight: 500; color: #111827;">
                    ${notification.data.message}
                </p>
                <p style="color: #9CA3AF; font-size: 0.75rem; margin-top: 0.25rem;">
                    ${formatTime(notification.created_at)}
                </p>
            </div>
        </div>
    `;

    return item;
}

function getNotificationIcon(notification) {
    const type = notification.data.type;
    let iconHtml = '';

    switch(type) {
        case 'wishlist':
            iconHtml = '<svg class="notification-icon text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
            break;
        case 'review':
            iconHtml = '<svg class="notification-icon text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>';
            break;
        case 'new_order':
            iconHtml = '<svg class="notification-icon text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            break;
        default:
            iconHtml = '<svg class="notification-icon text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>';
    }

    return iconHtml;
}

function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'Baru saja';
    if (minutes < 60) return `${minutes} menit yang lalu`;
    if (hours < 24) return `${hours} jam yang lalu`;
    return `${days} hari yang lalu`;
}

function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-title">${getToastTitle(type)}</div>
            <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
        <div class="toast-body">${message}</div>
    `;

    container.appendChild(toast);

    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 100);

    // Auto remove
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

function getToastTitle(type) {
    const titles = {
        'success': 'Berhasil',
        'error': 'Error',
        'warning': 'Peringatan',
        'info': 'Info'
    };
    return titles[type] || 'Notifikasi';
}

function handleNotificationClick(id, url) {
    // Kirim request untuk menandai notifikasi sebagai telah dibaca
    fetch(`/notifications/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        // Redirect ke URL terkait
        if (url && url !== '#') {
            window.location.href = url;
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        window.location.reload();
    });
}

// Real-time notifications using Server-Sent Events
let eventSource = null;

function connectToNotificationStream() {
    if (eventSource) {
        eventSource.close();
    }

    eventSource = new EventSource('/notifications/stream?last_count=' + lastNotificationCount);

    eventSource.onmessage = function(event) {
        try {
            const data = JSON.parse(event.data);
            updateNotificationBadge(data.count);

            // Show toast for new notifications
            if (data.new_count > 0) {
                showToast(`Anda memiliki ${data.new_count} notifikasi baru`, 'info');

                // Reload notifications if dropdown is open
                if (isDropdownOpen) {
                    loadNotifications();
                }
            }

            lastNotificationCount = data.count;
        } catch (error) {
            console.error('Error parsing SSE data:', error);
        }
    };

    eventSource.onerror = function(error) {
        console.error('SSE connection error:', error);
        // Fallback to polling after 30 seconds
        setTimeout(() => {
            if (!eventSource || eventSource.readyState === EventSource.CLOSED) {
                fallbackPolling();
            }
        }, 30000);
    };

    eventSource.onopen = function() {
        console.log('SSE connection opened');
    };
}

function fallbackPolling() {
    console.log('Falling back to polling');
    setInterval(function() {
        fetch('/notifications/check')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count);

                // Show toast for new notifications
                if (data.count > lastNotificationCount && lastNotificationCount > 0) {
                    showToast(`Anda memiliki ${data.count - lastNotificationCount} notifikasi baru`, 'info');
                }

                lastNotificationCount = data.count;
            })
            .catch(error => console.error('Error checking notifications:', error));
    }, 30000);
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Initialize real-time connection
connectToNotificationStream();

// Click outside to close dropdown
document.addEventListener('click', function(event) {
    const panel = document.querySelector('.notification-panel');
    const isClickInside = panel.contains(event.target);

    if (!isClickInside && isDropdownOpen) {
        toggleNotifications();
    }
});
</script>
