<?php

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
// Define Environment Settings (Development, Staging, Production)
define('ENVIRONMENT', 'development');  // Change to 'staging' or 'production' as needed

// General Application Settings
define('APP_NAME', 'SaveEntries');
define('APP_URL', 'http://localhost/save-entries');  // Change for production, e.g., 'https://yourdomain.com'
define('APP_DEBUG', true); // Set to false in production
define('APP_LOG_PATH', __DIR__ . '/../logs/');

// Database Configuration (For local, staging, and production)
define('DB_HOST', 'localhost');  // Update with your DB host
define('DB_USERNAME', 'root');    // Update with your DB username
define('DB_PASSWORD', '');        // Update with your DB password
define('DB_DATABASE', 'save_entries');  // Update with your database name
define('DB_PORT', 3306);          // Default MySQL port, change if using a different DB

// Email Configuration (For sending system emails)
define('MAIL_HOST', 'smtp.mailtrap.io'); // Replace with your mail service
define('MAIL_PORT', 587);  // Default port for SMTP
define('MAIL_USERNAME', 'your_username');  // Replace with your SMTP username
define('MAIL_PASSWORD', 'your_password');  // Replace with your SMTP password
define('MAIL_FROM', 'noreply@yourdomain.com');  // System sender email address
define('MAIL_FROM_NAME', 'YourAppName');

// Session Settings
define('SESSION_LIFETIME', 3600);  // Session timeout in seconds (1 hour)
define('SESSION_NAME', 'user_session');  // The session name for user tracking
define('SESSION_SECURE', true);  // Set to true for secure sessions (HTTPS)
define('SESSION_HTTP_ONLY', true);  // Prevents JavaScript from accessing session data

// File Upload Settings
define('UPLOAD_DIR', '/path/to/uploads/');  // Directory to store uploaded files
define('MAX_UPLOAD_SIZE', 10485760); // Maximum upload file size (10MB in bytes)
define('ALLOWED_FILE_TYPES', ['image/jpeg', 'image/png', 'application/pdf']); // Allowed file types

// Cache Settings
define('CACHE_ENABLED', true);  // Enable or disable caching
define('CACHE_PATH', __DIR__ . '/../cache/');  // Path to store cached data
define('CACHE_LIFETIME', 86400);  // Cache lifetime in seconds (1 day)

// Security Settings
define('ENCRYPTION_KEY', 'your-encryption-key');  // Strong secret key for encryption
define('CSRF_TOKEN', true); // Enable CSRF protection
define('XSS_FILTER', true); // Enable Cross-Site Scripting (XSS) protection

// Logging Configuration
define('LOG_ENABLED', true);  // Enable logging
define('LOG_LEVEL', 'debug'); // Set log level: debug, info, warning, error, critical, emergency
define('LOG_ROTATE', true); // Enable log rotation (to prevent file size bloat)
define('LOG_MAX_SIZE', 10485760);  // Maximum log file size (10MB)
define('LOG_RETENTION_DAYS', 30);  // Number of days to retain logs before deletion

// External API Configuration
define('API_KEY', 'your-api-key'); // Replace with your API key for third-party integrations
define('API_SECRET', 'your-api-secret'); // Replace with your API secret

// CDN and Static Asset Configuration
define('CDN_URL', 'https://cdn.yourdomain.com'); // URL to your content delivery network
define('STATIC_FILE_PATH', '/path/to/static/');  // Path to static assets (CSS, JS, Images)

// Authentication and Authorization Settings
define('AUTH_TIMEOUT', 3600);  // Authentication timeout in seconds (1 hour)
define('MAX_LOGIN_ATTEMPTS', 5);  // Max failed login attempts before account lockout
define('PASSWORD_MIN_LENGTH', 8);  // Minimum password length
define('PASSWORD_MAX_LENGTH', 20);  // Maximum password length
define('PASSWORD_COMPLEXITY', 'medium');  // Password complexity: low, medium, high

// Debug and Error Handling (Development, Staging, Production)
if (ENVIRONMENT == 'development') {
    error_reporting(E_ALL);  // Report all errors in development
    ini_set('display_errors', 1);  // Display errors on the screen
} elseif (ENVIRONMENT == 'staging') {
    error_reporting(E_ALL & ~E_NOTICE);  // Report all errors except notices
    ini_set('display_errors', 0);  // Do not display errors on the screen
} else { // Production Environment
    error_reporting(E_ALL & ~E_NOTICE);  // Report all errors except notices
    ini_set('display_errors', 0);  // Do not display errors on the screen
    // Log errors to file for production
    ini_set('log_errors', 1);
    ini_set('error_log', APP_LOG_PATH . 'prod_error.log');
}

// Set the time zone to Asia/Karachi
date_default_timezone_set('Asia/Karachi');

// Handle Log Rotation (Optional)
if (LOG_ROTATE) {
    require_once __DIR__ . '/../vendor/autoload.php';  // Make sure Monolog is loaded here

    // Initialize the log handler
    $logHandler = new RotatingFileHandler(APP_LOG_PATH . 'app_log.log', LOG_RETENTION_DAYS, Logger::toMonologLevel(LOG_LEVEL), true);
    
    // Initialize the Logger with the correct log level
    $log = new Logger(APP_NAME);
    $log->pushHandler($logHandler);
    // Log initialization can be done here...
}

// Enable or Disable Cache
if (CACHE_ENABLED) {
    // Define the cache directory and lifetime
    $cacheDir = CACHE_PATH;
    $cacheLifetime = CACHE_LIFETIME;

    // Check if the cache directory exists, create it if not
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    // Cache key to identify the data
    $cacheKey = 'my_cache_key';  // You should generate unique keys based on the content or request

    // Define the cache file path
    $cacheFile = $cacheDir . $cacheKey . '.cache';

    // Check if cache file exists and is still valid
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheLifetime) {
        // Cache is valid, read from the cache file
        $cachedData = file_get_contents($cacheFile);
        // echo "Cache Hit: " . $cachedData;  // Use cached data
    } else {
        // Cache is not valid or doesn't exist, perform the actual logic (e.g., DB query or API call)
        $data = "This is the freshly fetched data";  // Example data (replace with actual logic)

        // Save the data to cache
        file_put_contents($cacheFile, $data);
        // echo "Cache Miss: " . $data;  // Output fresh data
    }
}

// Automatically Log Every Action
if (LOG_ENABLED) {
    // Check if the logger is already initialized
    if (!isset($GLOBALS['logger'])) {
        include_once __DIR__ . '/../classes/Logger.php';  // Include Logger class
        
        // Instantiate the logger class
        $GLOBALS['logger'] = new Logger(APP_NAME);
        $GLOBALS['logger']->pushHandler(new RotatingFileHandler(APP_LOG_PATH . 'app_log.log', LOG_RETENTION_DAYS, Logger::toMonologLevel(LOG_LEVEL)));
    }

    // Log a basic entry to indicate the script was accessed
    $GLOBALS['logger']->info('File included and script started: ' . basename($_SERVER['PHP_SELF']));
}

?>
