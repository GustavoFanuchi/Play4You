<?php
class Security {
    
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public static function checkSessionTimeout() {
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            session_destroy();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public static function preventBruteForce($identifier) {
        $key = 'login_attempts_' . $identifier;
        $attempts = $_SESSION[$key] ?? 0;
        
        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $lockout_time = $_SESSION[$key . '_lockout'] ?? 0;
            if (time() - $lockout_time < 900) { // 15 minutes lockout
                return false;
            } else {
                unset($_SESSION[$key], $_SESSION[$key . '_lockout']);
            }
        }
        
        return true;
    }
    
    public static function recordFailedLogin($identifier) {
        $key = 'login_attempts_' . $identifier;
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
        
        if ($_SESSION[$key] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION[$key . '_lockout'] = time();
        }
    }
    
    public static function clearFailedLogins($identifier) {
        $key = 'login_attempts_' . $identifier;
        unset($_SESSION[$key], $_SESSION[$key . '_lockout']);
    }
}
?>
