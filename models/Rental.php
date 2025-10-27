<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class Rental extends Model {
    protected $table = 'rentals';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getRentalRepository();
    }
    
    public function getRentalDetails($id) {
        return $this->repository->getRentalDetails($id);
    }
    
    public function getUserRentals($userId, $limit = 10, $offset = 0) {
        return $this->repository->getUserRentals($userId, $limit, $offset);
    }
    
    public function getOwnerRentals($userId, $limit = 10, $offset = 0) {
        return $this->repository->getOwnerRentals($userId, $limit, $offset);
    }
    
    public function countOwnerRentals($userId) {
        return $this->repository->countOwnerRentals($userId);
    }
    
    public function getTotalEarnings($userId) {
        return $this->repository->getTotalEarnings($userId);
    }
    
    public function countActiveRentals($userId) {
        return $this->repository->countActiveRentals($userId);
    }
    
    public function countUserRentals($userId) {
        return $this->repository->countUserRentals($userId);
    }
    
    public function isProductAvailable($productId, $startDate, $endDate) {
        return $this->repository->isProductAvailable($productId, $startDate, $endDate);
    }
    
    public function getUpcomingRentals($userId, $limit = 5) {
        return $this->repository->getUpcomingRentals($userId, $limit);
    }
    
    // Métodos adicionais usando o repository
    public function updateStatus($id, $status) {
        return $this->repository->updateStatus($id, $status);
    }
    
    public function getRentalsByStatus($status, $userId = null, $limit = 20, $offset = 0) {
        return $this->repository->getRentalsByStatus($status, $userId, $limit, $offset);
    }
    
    public function getExpiringRentals($days = 3) {
        return $this->repository->getExpiringRentals($days);
    }
    
    public function getRentalStats($userId) {
        return $this->repository->getRentalStats($userId);
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