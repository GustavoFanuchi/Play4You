<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class Chat extends Model {
    protected $table = 'chats';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getChatRepository();
    }
    
    public function findOrCreateChat($user1Id, $user2Id, $productId = null) {
        return $this->repository->findOrCreateChat($user1Id, $user2Id, $productId);
    }
    
    public function getUserChats($userId) {
        return $this->repository->getUserChats($userId);
    }
    
    public function getChatWithUsers($chatId) {
        return $this->repository->getChatWithUsers($chatId);
    }
    
    // Métodos adicionais usando o repository
    public function updateLastMessage($chatId, $messageId) {
        return $this->repository->updateLastMessage($chatId, $messageId);
    }
    
    public function getChatsWithUnreadMessages($userId) {
        return $this->repository->getChatsWithUnreadMessages($userId);
    }
    
    public function userHasAccess($chatId, $userId) {
        return $this->repository->userHasAccess($chatId, $userId);
    }
    
    public function getChatsByProduct($productId) {
        return $this->repository->getChatsByProduct($productId);
    }
    
    public function deleteChat($chatId) {
        return $this->repository->deleteChat($chatId);
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