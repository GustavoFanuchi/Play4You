<?php
/**
 * Exemplo de como usar os repositories diretamente nos controllers
 * 
 * Este arquivo demonstra diferentes formas de usar os repositories:
 * 1. Através dos models (recomendado para compatibilidade)
 * 2. Diretamente nos controllers (para casos mais avançados)
 */

require_once __DIR__ . '/../repositories/RepositoryManager.php';

class RepositoryUsageExample {
    
    /**
     * Exemplo 1: Usando através dos models (recomendado)
     */
    public function exampleUsingModels() {
        // Os models agora usam repositories internamente
        $userModel = new User();
        $user = $userModel->findByEmail('usuario@exemplo.com');
        
        $productModel = new Product();
        $products = $productModel->getFeaturedProducts(8);
        
        $rentalModel = new Rental();
        $rentals = $rentalModel->getUserRentals(1, 10, 0);
    }
    
    /**
     * Exemplo 2: Usando repositories diretamente
     */
    public function exampleUsingRepositoriesDirectly() {
        // Obter repositories
        $userRepo = RepositoryManager::getUserRepository();
        $productRepo = RepositoryManager::getProductRepository();
        $rentalRepo = RepositoryManager::getRentalRepository();
        $chatRepo = RepositoryManager::getChatRepository();
        $messageRepo = RepositoryManager::getMessageRepository();
        $categoryRepo = RepositoryManager::getCategoryRepository();
        
        // Operações de usuário
        $user = $userRepo->findByEmail('usuario@exemplo.com');
        $userProfile = $userRepo->getProfile(1);
        $activeUsers = $userRepo->findActiveUsers(10);
        
        // Operações de produto
        $featuredProducts = $productRepo->getFeaturedProducts(8);
        $searchResults = $productRepo->searchProducts(['search' => 'iPhone'], 20, 0);
        $productDetails = $productRepo->getProductDetails(1);
        
        // Operações de aluguel
        $userRentals = $rentalRepo->getUserRentals(1, 10, 0);
        $totalEarnings = $rentalRepo->getTotalEarnings(1);
        $isAvailable = $rentalRepo->isProductAvailable(1, '2024-01-01', '2024-01-05');
        
        // Operações de chat
        $userChats = $chatRepo->getUserChats(1);
        $chat = $chatRepo->findOrCreateChat(1, 2, 1);
        $messages = $messageRepo->getChatMessages(1, 50);
        
        // Operações de categoria
        $categories = $categoryRepo->getWithProductCount();
        $popularCategories = $categoryRepo->getPopularCategories(10);
    }
    
    /**
     * Exemplo 3: Usando transações
     */
    public function exampleUsingTransactions() {
        $userRepo = RepositoryManager::getUserRepository();
        $productRepo = RepositoryManager::getProductRepository();
        
        try {
            // Iniciar transação
            $userRepo->beginTransaction();
            
            // Criar usuário
            $userId = $userRepo->create([
                'name' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'password' => password_hash('senha123', PASSWORD_DEFAULT)
            ]);
            
            // Criar produto
            $productId = $productRepo->create([
                'title' => 'iPhone 13',
                'description' => 'Smartphone em excelente estado',
                'user_id' => $userId,
                'category_id' => 1,
                'daily_price' => 50.00
            ]);
            
            // Confirmar transação
            $userRepo->commit();
            
            return ['user_id' => $userId, 'product_id' => $productId];
            
        } catch (Exception $e) {
            // Desfazer transação em caso de erro
            $userRepo->rollback();
            throw $e;
        }
    }
    
    /**
     * Exemplo 4: Busca complexa com múltiplos repositories
     */
    public function exampleComplexSearch() {
        $userRepo = RepositoryManager::getUserRepository();
        $productRepo = RepositoryManager::getProductRepository();
        $rentalRepo = RepositoryManager::getRentalRepository();
        
        // Buscar usuário
        $user = $userRepo->findById(1);
        
        if ($user) {
            // Buscar produtos do usuário
            $userProducts = $productRepo->getUserProducts($user['id']);
            
            // Buscar aluguéis do usuário
            $userRentals = $rentalRepo->getUserRentals($user['id'], 10, 0);
            
            // Buscar estatísticas
            $rentalStats = $rentalRepo->getRentalStats($user['id']);
            
            return [
                'user' => $user,
                'products' => $userProducts,
                'rentals' => $userRentals,
                'stats' => $rentalStats
            ];
        }
        
        return null;
    }
    
    /**
     * Exemplo 5: Operações em lote
     */
    public function exampleBatchOperations() {
        $productRepo = RepositoryManager::getProductRepository();
        
        // Buscar produtos que precisam ser atualizados
        $products = $productRepo->findBy(['is_available' => 1], 100);
        
        $updated = 0;
        foreach ($products as $product) {
            // Atualizar disponibilidade baseada em aluguéis ativos
            $isAvailable = $this->checkProductAvailability($product['id']);
            
            if ($product['is_available'] != $isAvailable) {
                $productRepo->updateAvailability($product['id'], $isAvailable);
                $updated++;
            }
        }
        
        return $updated;
    }
    
    private function checkProductAvailability($productId) {
        $rentalRepo = RepositoryManager::getRentalRepository();
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        return $rentalRepo->isProductAvailable($productId, $today, $tomorrow);
    }
}

// Exemplo de uso em um controller
class ExampleController extends Controller {
    
    public function dashboard() {
        $userRepo = RepositoryManager::getUserRepository();
        $productRepo = RepositoryManager::getProductRepository();
        $rentalRepo = RepositoryManager::getRentalRepository();
        
        $userId = $_SESSION['user_id'] ?? null;
        
        if ($userId) {
            $user = $userRepo->getProfile($userId);
            $userProducts = $productRepo->getUserProducts($userId);
            $userRentals = $rentalRepo->getUserRentals($userId, 5, 0);
            $upcomingRentals = $rentalRepo->getUpcomingRentals($userId, 5);
            
            $this->view('dashboard/index', [
                'title' => 'Dashboard - Play4You',
                'user' => $user,
                'products' => $userProducts,
                'rentals' => $userRentals,
                'upcoming' => $upcomingRentals
            ]);
        } else {
            $this->redirect('/login');
        }
    }
}
?>

