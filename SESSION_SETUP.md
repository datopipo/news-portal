# Session Management Setup

## Overview

This project uses Symfony's built-in session management with enhanced security features. Sessions are automatically created on login and destroyed on logout using Symfony's security component.

## Configuration

### 1. Session Configuration (config/packages/framework.yaml)

```yaml
session:
    enabled: true
    handler_id: null # Use default file-based session handler
    cookie_secure: auto # Secure cookies in production
    cookie_samesite: lax
    cookie_httponly: true
    cookie_lifetime: 3600 # 1 hour session lifetime
    gc_maxlifetime: 3600 # Garbage collection after 1 hour
    name: PHPSESSID
    save_path: '%kernel.project_dir%/var/sessions/%kernel.environment%'
```

### 2. Security Configuration (config/packages/security.yaml)

```yaml
security:
    session_fixation_strategy: migrate # Prevents session fixation attacks
    
    firewalls:
        admin:
            form_login:
                enable_csrf: true # CSRF protection for login
            logout:
                invalidate_session: true # Explicitly destroy session
                delete_cookies:
                    PHPSESSID: { path: /, domain: ~ }
```

## Environment Setup

Create a `.env.local` file in your project root with admin credentials:

```bash
# Admin credentials
ADMIN_USERNAME=admin
ADMIN_PASSWORD_HASH=$2y$13$your_bcrypt_hashed_password_here

# Generate password hash using Symfony console:
# php bin/console security:hash-password
```

To generate a password hash:
```bash
php bin/console security:hash-password
```

## How It Works

### Automatic Session Management

1. **Login Process:**
   - User submits login form
   - Symfony validates credentials
   - **Session is automatically created**
   - User is redirected to admin dashboard
   - Session data is stored server-side

2. **Session During Usage:**
   - Session ID is stored in cookie (PHPSESSID)
   - Session data persists across requests
   - Activity timestamps are updated
   - CSRF tokens are managed automatically

3. **Logout Process:**
   - User clicks logout or visits /admin/logout
   - **Session is automatically invalidated**
   - Session data is cleared from server
   - Session cookie is deleted
   - User is redirected to public page

### Manual Session Management

You can also work with sessions programmatically using the SessionManager service:

```php
// In a controller
public function someAction(SessionManager $sessionManager): Response
{
    // Check if user is logged in
    if ($sessionManager->isUserLoggedIn()) {
        // Get session information
        $sessionData = $sessionManager->getSessionData();
        
        // Update activity timestamp
        $sessionManager->updateActivity();
        
        // Get session statistics
        $stats = $sessionManager->getSessionStats();
    }
    
    // Manual logout (if needed)
    // $sessionManager->destroySession();
}
```

## Session Security Features

✅ **Session Fixation Protection**: Sessions are migrated on login  
✅ **CSRF Protection**: Login forms are protected against CSRF attacks  
✅ **Secure Cookies**: HTTPOnly and Secure flags set automatically  
✅ **Session Timeout**: Sessions expire after 1 hour of inactivity  
✅ **Automatic Cleanup**: Old sessions are garbage collected  
✅ **Cookie Security**: SameSite protection enabled  

## Session Storage

- **Location**: `var/sessions/dev/` (development) or `var/sessions/prod/` (production)
- **Format**: PHP serialize format (secure)
- **Cleanup**: Automatic garbage collection removes expired sessions

## Usage Examples

### In Controllers

```php
use Symfony\Component\HttpFoundation\Session\SessionInterface;

public function dashboard(SessionInterface $session): Response
{
    // Store data in session
    $session->set('user_preference', 'dark_mode');
    
    // Get data from session
    $preference = $session->get('user_preference', 'light_mode');
    
    // Check if session has specific data
    if ($session->has('welcomed')) {
        // User has been welcomed before
    }
    
    // Flash messages (stored in session temporarily)
    $this->addFlash('success', 'Action completed successfully!');
}
```

### Session Information

Access session information in templates:

```twig
{# In your Twig template #}
{% if app.user %}
    <p>Welcome, {{ app.user.userIdentifier }}!</p>
    <p>Session ID: {{ app.session.id }}</p>
{% endif %}
```

## Testing Session Management

1. **Login Test:**
   - Visit `/admin/login`
   - Enter credentials
   - Check that session cookie is set
   - Verify access to protected pages

2. **Session Persistence:**
   - Navigate between admin pages
   - Check that session persists
   - Verify session data is maintained

3. **Logout Test:**
   - Click logout
   - Verify session is destroyed
   - Check that protected pages are inaccessible
   - Confirm cookie is removed

## Troubleshooting

### Session Directory Permissions
```bash
# Ensure session directory is writable
mkdir -p var/sessions/dev
chmod 755 var/sessions/dev
```

### Clear Sessions
```bash
# Clear all sessions
rm -rf var/sessions/*
```

### Debug Session Issues
```bash
# Check current sessions
ls -la var/sessions/dev/

# View Symfony profiler for session details
# Available at /_profiler when in debug mode
```

## Production Considerations

1. **Use Redis/Database for Session Storage** (for multiple servers):
```yaml
# config/packages/framework.yaml
framework:
    session:
        handler_id: 'redis://localhost:6379'
```

2. **Configure Proper Session Garbage Collection**
3. **Set Up HTTPS** (for secure cookie transmission)
4. **Monitor Session Storage Usage**

This setup provides robust, secure session management using Symfony's built-in capabilities with minimal complexity. 