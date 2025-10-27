<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class User extends Model {
    protected $table = 'users';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getUserRepository();
    }
    
    public function findByEmail($email) {
        return $this->repository->findByEmail($email);
    }
    
    public function updateLastLogin($userId) {
        return $this->repository->updateLastLogin($userId);
    }
    
    public function getProfile($userId) {
        return $this->repository->getProfile($userId);
    }
    
    public function updateProfile($userId, $data) {
        return $this->repository->updateProfile($userId, $data);
    }
    
    // Métodos adicionais usando o repository
    public function findByEmailAndPassword($email, $password) {
        return $this->repository->findByEmailAndPassword($email, $password);
    }
    
    public function updatePassword($userId, $newPassword) {
        return $this->repository->updatePassword($userId, $newPassword);
    }
    
    public function emailExists($email, $excludeUserId = null) {
        return $this->repository->emailExists($email, $excludeUserId);
    }
    
    public function searchByName($name, $limit = 10) {
        return $this->repository->searchByName($name, $limit);
    }
    
    public function findActiveUsers($limit = null, $offset = 0) {
        return $this->repository->findActiveUsers($limit, $offset);
    }
    
    public function updateStatus($userId, $status) {
        return $this->repository->updateStatus($userId, $status);
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
