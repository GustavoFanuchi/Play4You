<?php
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ . '/ProductRepository.php';
require_once __DIR__ . '/RentalRepository.php';
require_once __DIR__ . '/ChatRepository.php';
require_once __DIR__ . '/MessageRepository.php';
require_once __DIR__ . '/CategoryRepository.php';

class RepositoryManager {
    private static $instances = [];
    
    /**
     * Obtém instância do UserRepository
     */
    public static function getUserRepository() {
        if (!isset(self::$instances['user'])) {
            self::$instances['user'] = new UserRepository();
        }
        return self::$instances['user'];
    }
    
    /**
     * Obtém instância do ProductRepository
     */
    public static function getProductRepository() {
        if (!isset(self::$instances['product'])) {
            self::$instances['product'] = new ProductRepository();
        }
        return self::$instances['product'];
    }
    
    /**
     * Obtém instância do RentalRepository
     */
    public static function getRentalRepository() {
        if (!isset(self::$instances['rental'])) {
            self::$instances['rental'] = new RentalRepository();
        }
        return self::$instances['rental'];
    }
    
    /**
     * Obtém instância do ChatRepository
     */
    public static function getChatRepository() {
        if (!isset(self::$instances['chat'])) {
            self::$instances['chat'] = new ChatRepository();
        }
        return self::$instances['chat'];
    }
    
    /**
     * Obtém instância do MessageRepository
     */
    public static function getMessageRepository() {
        if (!isset(self::$instances['message'])) {
            self::$instances['message'] = new MessageRepository();
        }
        return self::$instances['message'];
    }
    
    /**
     * Obtém instância do CategoryRepository
     */
    public static function getCategoryRepository() {
        if (!isset(self::$instances['category'])) {
            self::$instances['category'] = new CategoryRepository();
        }
        return self::$instances['category'];
    }
    
    /**
     * Obtém todos os repositories disponíveis
     */
    public static function getAllRepositories() {
        return [
            'user' => self::getUserRepository(),
            'product' => self::getProductRepository(),
            'rental' => self::getRentalRepository(),
            'chat' => self::getChatRepository(),
            'message' => self::getMessageRepository(),
            'category' => self::getCategoryRepository()
        ];
    }
    
    /**
     * Limpa todas as instâncias (útil para testes)
     */
    public static function clearInstances() {
        self::$instances = [];
    }
}
?>
