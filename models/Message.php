<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class Message extends Model {
    protected $table = 'messages';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getMessageRepository();
    }
    
    public function getChatMessages($chatId, $limit = 50) {
        return $this->repository->getChatMessages($chatId, $limit);
    }
    
    public function getNewMessages($chatId, $lastMessageId) {
        return $this->repository->getNewMessages($chatId, $lastMessageId);
    }
    
    public function getMessageWithUser($messageId) {
        return $this->repository->getMessageWithUser($messageId);
    }
    
    public function markAsRead($chatId, $userId) {
        return $this->repository->markAsRead($chatId, $userId);
    }
    
    public function getUnreadCount($userId) {
        return $this->repository->getUnreadCount($userId);
    }
    
    // Métodos adicionais usando o repository
    public function getLastMessage($chatId) {
        return $this->repository->getLastMessage($chatId);
    }
    
    public function getUnreadMessages($chatId, $userId) {
        return $this->repository->getUnreadMessages($chatId, $userId);
    }
    
    public function getMessagesByDateRange($chatId, $startDate, $endDate) {
        return $this->repository->getMessagesByDateRange($chatId, $startDate, $endDate);
    }
    
    public function getTextMessages($chatId, $limit = 50) {
        return $this->repository->getTextMessages($chatId, $limit);
    }
    
    public function getMessagesByType($chatId, $messageType, $limit = 50) {
        return $this->repository->getMessagesByType($chatId, $messageType, $limit);
    }
    
    public function deleteMessage($messageId, $userId) {
        return $this->repository->deleteMessage($messageId, $userId);
    }
    
    public function getMessageStats($userId) {
        return $this->repository->getMessageStats($userId);
    }
    
    // Métodos CRUD básicos usando o repository
    public function find($id) {
        return $this->repository->findById($id);
    }
    
    public function create($data) {
        return $this->repository->create($data);
    }
    
    public function update($id, $data) {
        return $this->repository->update($id, $data);
    }
    
    public function delete($id) {
        return $this->repository->delete($id);
    }
    
    public function findAll($conditions = [], $limit = null, $offset = null) {
        return $this->repository->findAll($limit, $offset);
    }
}
?>