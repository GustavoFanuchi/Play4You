<?php
// System constants
define('SITE_NAME', 'Play4You');
define('SITE_URL', 'http://localhost');
define('SITE_EMAIL', 'contato@play4you.com');
define('SITE_PHONE', '(11) 99999-9999');

// File upload settings
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Pagination settings
define('PRODUCTS_PER_PAGE', 12);
define('REVIEWS_PER_PAGE', 10);
define('MESSAGES_PER_PAGE', 50);

// Rental settings
define('MIN_RENTAL_DAYS', 1);
define('MAX_RENTAL_DAYS', 30);
define('INSURANCE_RATE', 0.10); // 10%
define('SERVICE_FEE', 15.00);

// Security settings
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('PASSWORD_MIN_LENGTH', 6);

// Email settings (for future implementation)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// Payment settings (for future implementation)
define('PAYMENT_GATEWAY', 'mercadopago'); // or 'paypal', 'mercadopago'
define('CURRENCY', 'BRL');

// Cache settings
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hour

// Debug settings
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('ERROR_LOG_PATH', 'logs/error.log');
?>
