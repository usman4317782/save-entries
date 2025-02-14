<?php
// echo "config file added";
// Define the base path of the project
define('BASE_PATH', dirname(__FILE__));
// echo BASE_PATH;
// Define the URL path (adjust according to your server setup)
define('BASE_URL', 'https://localhost/save-entries');
define('EMAIL_VERIFICATION_URL', 'https://localhost/save-entries/email-verification.php');
define('PASSWORD_RESET_URL', 'https://localhost/save-entries/reset-forgot-password.php');
// define('PASSWORD_RESET_URL', 'http://localhost/projects/vu_students/anam/anam-php-student-badge-2024/reset-forgot-password.php');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'saveentries');
define('DB_USER', 'root');
define('DB_PASS', '');

// Time zone setting
// Set default timezone
date_default_timezone_set('Asia/Karachi');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// Define common directories
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('MODELS_PATH', BASE_PATH . '/models');
define('VIEWS_PATH', BASE_PATH . '/views');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Define URL paths for assets
define('CSS_URL', BASE_URL . '/assets/css');
define('JS_URL', BASE_URL . '/assets/js');
define('IMAGES_URL', BASE_URL . '/assets/images');

// Application-specific settings
define('APP_NAME', 'Your Application Name');
define('APP_VERSION', '1.0.0');

// Security settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LENGTH', 32);

// Load environment-specific configuration
$env = getenv('APPLICATION_ENV') ?: 'development';
if (file_exists(BASE_PATH . "/config.{$env}.php")) {
    require_once BASE_PATH . "/config.{$env}.php";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// You can add more configuration settings as needed

// session_start();