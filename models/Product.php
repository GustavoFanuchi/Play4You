<?php
require_once __DIR__ . '/../repositories/RepositoryManager.php';

class Product extends Model {
    protected $table = 'products';
    private $repository;
    
    public function __construct() {
        parent::__construct();
        $this->repository = RepositoryManager::getProductRepository();
    }
    
    public function getFeaturedProducts($limit = 8) {
        return $this->repository->getFeaturedProducts($limit);
    }
    
    public function searchProducts($filters = [], $limit = 20, $offset = 0) {
        return $this->repository->searchProducts($filters, $limit, $offset);
    }
    
    public function countSearchResults($filters = []) {
        return $this->repository->countSearchResults($filters);
    }
    
    public function getProductDetails($id) {
        return $this->repository->getProductDetails($id);
    }
    
    public function getSimilarProducts($productId, $categoryId, $limit = 4) {
        return $this->repository->getSimilarProducts($productId, $categoryId, $limit);
    }
    
    public function getAllBrands() {
        return $this->repository->getAllBrands();
    }
    
    public function incrementViews($id) {
        return $this->repository->incrementViews($id);
    }
    
    public function getUserProducts($userId) {
        return $this->repository->getUserProducts($userId);
    }
    
    public function countUserProducts($userId) {
        return $this->repository->countUserProducts($userId);
    }
    
    // Métodos adicionais usando o repository
    public function updateAvailability($id, $isAvailable) {
        return $this->repository->updateAvailability($id, $isAvailable);
    }
    
    public function getProductsByCategory($categoryId, $limit = 20, $offset = 0) {
        return $this->repository->getProductsByCategory($categoryId, $limit, $offset);
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