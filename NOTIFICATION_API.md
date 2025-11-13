# Real-Time Notification API Documentation

## Overview
The Wistara Laravel application includes a comprehensive real-time notification system that alerts administrators about user activities such as new orders, wishlist additions, and product reviews.

## Features
- **Real-time notifications** using Server-Sent Events (SSE)
- **Fallback polling** for browsers that don't support SSE
- **Toast notifications** with popup alerts
- **Database-backed** notification storage
- **REST API** for external integrations

## Notification Types
- `wishlist`: When users add products to their wishlist
- `review`: When users submit new product reviews
- `new_order`: When users place new orders
- `order_status`: When order status changes (existing)

## Email & WhatsApp Notifications

### New Order Alerts
When customers place new orders, admins receive comprehensive notifications:

#### 📧 Email Notifications
- **Subject**: "🆕 Pesanan Baru #{ID} — Batik Wistara"
- **Content**: Complete order details, customer info, payment status
- **Template**: `resources/views/emails/new_order.blade.php`
- **Features**: Professional styling, urgent alerts, contact info

#### 📱 WhatsApp Notifications
- **Format**: Immediate alert with order summary
- **Content**: Order ID, customer name, total amount
- **API**: Fonnte WhatsApp API integration
- **Example**:
  ```
  🆕 PESANAN BARU!

  ID: #WST-20251113-ABC
  Customer: John Doe
  Total: Rp 150,000

  Segera cek dashboard untuk detail lengkap.
  ```

### Configuration
```php
// Automatic in CheckoutController
$admin->notify(new NewOrderNotification($order, $user));
$this->sendWhatsapp($admin->phone, "🆕 PESANAN BARU!\n\nID: #{$order->id}\nCustomer: {$user->name}\nTotal: Rp " . number_format($order->total, 0, ',', '.') . "\n\nSegera cek dashboard untuk detail lengkap.");
```

## Web Interface (Frontend)

### Automatic Features
- **Live badge updates**: Notification count updates in real-time
- **Toast popups**: Instant alerts for new notifications
- **Dropdown loading**: Notifications load dynamically when dropdown opens
- **Auto-fallback**: Falls back to polling if SSE fails

### Manual Controls
- Mark individual notifications as read
- Mark all notifications as read
- Click notifications to navigate to relevant pages

## API Security & Authentication

### Authentication Methods
1. **Sanctum Bearer Tokens** (Recommended for API clients)
2. **Session-based** (Web interface)

### Rate Limiting
- **60 requests per minute** per authenticated user
- **Automatic throttling** prevents abuse
- **Graceful degradation** with proper error responses

### Security Features
- **Admin-only access** with email validation
- **Input validation** on all endpoints
- **SQL injection protection** via Eloquent ORM
- **XSS protection** with proper output escaping
- **CSRF protection** on web routes
- **CORS enabled** for cross-origin requests

### Getting Started

#### 1. Login & Get Token
```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@wistara.com",
  "password": "password"
}
```

**Response:**
```json
{
  "access_token": "your_token_here",
  "token_type": "Bearer"
}
```

#### 2. Use Token in Requests
```bash
Authorization: Bearer your_token_here
```

#### 3. Health Check
```bash
GET /api/health
```
**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2025-11-13T05:19:00.000000Z",
  "version": "1.0.0",
  "environment": "production",
  "database": "connected"
}
```

## API Endpoints

### Endpoints

#### 1. Get Notifications
```http
GET /api/v1/notifications?limit=20
Authorization: Bearer {token}
```

**Parameters:**
- `limit` (optional): Number of notifications to return (max 100, default 50)

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "new_order",
      "message": "Pesanan baru #WST-20251113-ABC dari John Doe",
      "order_id": "WST-20251113-ABC",
      "user_name": "John Doe",
      "total": 150000,
      "url": "/admin/pesanan/WST-20251113-ABC",
      "read": false,
      "created_at": "2025-11-13T05:00:00.000000Z"
    }
  ],
  "count": 1,
  "timestamp": "2025-11-13T05:19:00.000000Z"
}
```

**Error Responses:**
- `401`: `{"error": "Authentication required"}`
- `403`: `{"error": "Admin access required"}`
- `500`: `{"success": false, "error": "Internal server error"}`

#### 2. Get Unread Count
```http
GET /api/v1/notifications/count
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "count": 3
}
```

#### 3. Real-Time Stream (SSE)
```http
GET /api/v1/notifications/stream?last_count=0
Authorization: Bearer {token}
```

**Server-Sent Events Response:**
```
data: {"count":5,"new_count":2,"notifications":[...],"timestamp":1731474000}

data: {"count":6,"new_count":1,"notifications":[...],"timestamp":1731474003}
```

#### 4. Mark Notification as Read
```http
POST /api/v1/notifications/{id}/read
Authorization: Bearer {token}
```

**Parameters:**
- `id`: Notification ID (integer, required)

**Success Response (200):**
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

**Error Responses:**
- `400`: `{"error": "Invalid notification ID"}`
- `404`: `{"error": "Notification not found"}`

#### 5. Mark All as Read
```http
POST /api/v1/notifications/mark-all-read
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true
}
```

## JavaScript Integration

### Using Server-Sent Events (Recommended)
```javascript
const eventSource = new EventSource('/api/notifications/stream?last_count=0', {
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

eventSource.onmessage = function(event) {
  const data = JSON.parse(event.data);
  console.log('New notifications:', data.new_count);
  updateNotificationBadge(data.count);
  showToast(`You have ${data.new_count} new notifications`);
};

eventSource.onerror = function(error) {
  console.error('SSE Error:', error);
  // Fallback to polling
};
```

### Using Polling (Fallback)
```javascript
setInterval(async () => {
  const response = await fetch('/api/notifications/count', {
    headers: {
      'Authorization': 'Bearer ' + token
    }
  });
  const data = await response.json();
  updateNotificationBadge(data.count);
}, 30000); // Check every 30 seconds
```

## Database Structure

### Notifications Table
```sql
CREATE TABLE notifications (
  id char(36) NOT NULL,
  type varchar(255) NOT NULL,
  notifiable_type varchar(255) NOT NULL,
  notifiable_id bigint unsigned NOT NULL,
  data text NOT NULL,
  read_at timestamp NULL DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)
);
```

## Notification Classes

### WishlistNotification
Triggered when users add products to wishlist.

### ReviewNotification
Triggered when users submit product reviews.

### NewOrderNotification
Triggered when users place new orders.

### OrderStatusNotification
Triggered when order status changes (existing system).

## Configuration

### Environment Variables
```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@wistara.com"
MAIL_FROM_NAME="Batik Wistara"

# WhatsApp Configuration (Fonnte API)
FONNTE_TOKEN=your_fonnte_api_token_here
ADMIN_PHONE=628xxxxxxxxxx
```

### Email Setup
1. **Gmail SMTP**: Use app passwords for Gmail accounts
2. **Other Providers**: Configure SMTP settings accordingly
3. **Mailpit**: For local development, use `MAIL_MAILER=log` or Mailpit

### WhatsApp Setup (Fonnte)
1. Register at [fonnte.com](https://fonnte.com)
2. Get your API token
3. Set `FONNTE_TOKEN` in your `.env` file
4. Set `ADMIN_PHONE` with country code (e.g., 628xxxxxxxxxx)

### Admin Setup
Ensure admin users have the `Notifiable` trait:

```php
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    // ...
}
```

## Testing the API

### 1. Health Check
```bash
curl http://localhost:8000/api/health
```

### 2. Login and Get Token
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@wistara.com","password":"password"}'
```

### 3. Get Notifications
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/notifications
```

### 4. Get Unread Count
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/notifications/count
```

### 5. Mark as Read
```bash
curl -X POST -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/notifications/123/read
```

### 6. Test Real-Time Stream
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/notifications/stream
```

## Security Best Practices

### 🔐 Authentication
- Always use HTTPS in production
- Rotate tokens regularly
- Implement token expiration
- Use strong passwords

### 🛡️ Rate Limiting
- API is rate-limited to 60 requests/minute
- Implement exponential backoff for retries
- Monitor for abuse patterns

### ✅ Input Validation
- All inputs are validated server-side
- SQL injection prevented via Eloquent ORM
- XSS protection with output escaping

### 🚨 Error Handling
- Sensitive information never exposed in errors
- Proper HTTP status codes returned
- Logging for debugging without data leaks

### 🔒 CORS & Headers
- CORS enabled for cross-origin requests
- Security headers properly configured
- CSRF protection on web routes

## Troubleshooting

### SSE Not Working
- Check browser compatibility (SSE requires modern browsers)
- Ensure no proxy/firewall blocking streaming connections
- Falls back to polling automatically

### Notifications Not Appearing
- Verify admin has `Notifiable` trait
- Check database for notification records
- Ensure notification classes are properly imported

### API Authentication Issues
- Verify Sanctum token is valid
- Check admin role/permission logic
- Ensure proper middleware configuration

## Performance Considerations

- SSE connections are lightweight and efficient
- Notifications are stored in database for persistence
- Automatic cleanup of old notifications recommended
- Consider implementing notification preferences for admins
