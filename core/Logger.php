<?php
class Logger {
    
    public static function log($message, $level = 'INFO') {
        if (!LOG_ERRORS) return;
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        
        $logDir = dirname(ERROR_LOG_PATH);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents(ERROR_LOG_PATH, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    public static function error($message) {
        self::log($message, 'ERROR');
    }
    
    public static function warning($message) {
        self::log($message, 'WARNING');
    }
    
    public static function info($message) {
        self::log($message, 'INFO');
    }
    
    public static function debug($message) {
        if (DEBUG_MODE) {
            self::log($message, 'DEBUG');
        }
    }
}
?>
