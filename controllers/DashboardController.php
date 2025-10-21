<?php
require_once 'models/Product.php';
require_once 'models/Rental.php';
require_once 'models/User.php';

class DashboardController extends Controller {
    private $productModel;
    private $rentalModel;
    private $userModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->rentalModel = new Rental();
        $this->userModel = new User();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Get user's products
        $products = $this->productModel->getUserProducts($userId);
        
        // Get user's rentals (as owner)
        $rentals = $this->rentalModel->getOwnerRentals($userId);
        
        // Get statistics
        $stats = $this->getUserStats($userId);
        
        $this->view('dashboard/index', [
            'title' => 'Dashboard - Play4You',
            'products' => $products,
            'rentals' => $rentals,
            'stats' => $stats
        ]);
    }
    
    public function addProduct() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'category_id' => $_POST['category_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'condition_status' => $_POST['condition_status'],
                'daily_price' => floatval($_POST['daily_price']),
                'weekly_price' => floatval($_POST['weekly_price']),
                'monthly_price' => floatval($_POST['monthly_price']),
                'deposit_amount' => floatval($_POST['deposit_amount']),
                'location_city' => trim($_POST['location_city']),
                'location_state' => trim($_POST['location_state']),
                'delivery_available' => isset($_POST['delivery_available']) ? 1 : 0,
                'pickup_available' => isset($_POST['pickup_available']) ? 1 : 0,
                'policies' => trim($_POST['policies'])
            ];

            // Handle images upload (optional)
            $uploadedImages = [];
            if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
                $uploadDir = 'public/uploads/products';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }
                $maxFiles = 10;
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $count = min(count($_FILES['images']['name']), $maxFiles);
                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                    if ($_FILES['images']['size'][$i] > $maxSize) continue;
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $type = mime_content_type($tmpName);
                    if (!in_array($type, $allowedTypes, true)) continue;

                    // Definir a extensão exclusivamente pelo MIME type
                    $safeExt = 'jpg';
                    if ($type === 'image/png') {
                        $safeExt = 'png';
                    } elseif ($type === 'image/webp') {
                        $safeExt = 'webp';
                    } elseif ($type === 'image/jpeg') {
                        $safeExt = 'jpg';
                    }
                    $filename = uniqid('prod_', true) . '.' . $safeExt;
                    $destination = $uploadDir . '/' . $filename;
                    if (move_uploaded_file($tmpName, $destination)) {
                        $uploadedImages[] = $destination; // caminho relativo como em outros assets (ex.: public/LogoPlay4You.png)
                    }
                }
            }

            if (!empty($uploadedImages)) {
                $data['images'] = json_encode($uploadedImages, JSON_UNESCAPED_SLASHES);
                // Debug: verificar o que está sendo salvo
                error_log("Images JSON: " . $data['images']);
            }
            
            $productId = $this->productModel->create($data);
            
            if ($productId) {
                $_SESSION['success'] = 'Produto cadastrado com sucesso!';
                header('Location: /dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar produto.';
            }
        }
        
        // Get categories for the form
        $categoryModel = new Category();
        $categories = $categoryModel->findAll();
        
        $this->view('dashboard/add-product', [
            'title' => 'Cadastrar Produto - Play4You',
            'categories' => $categories
        ]);
    }
    
    public function editProduct($id) {
        $this->requireAuth();
        
        $product = $this->productModel->find($id);
        
        if (!$product || $product['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Produto não encontrado.';
            header('Location: /dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_id' => $_POST['category_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'condition_status' => $_POST['condition_status'],
                'daily_price' => floatval($_POST['daily_price']),
                'weekly_price' => floatval($_POST['weekly_price']),
                'monthly_price' => floatval($_POST['monthly_price']),
                'deposit_amount' => floatval($_POST['deposit_amount']),
                'location_city' => trim($_POST['location_city']),
                'location_state' => trim($_POST['location_state']),
                'delivery_available' => isset($_POST['delivery_available']) ? 1 : 0,
                'pickup_available' => isset($_POST['pickup_available']) ? 1 : 0,
                'policies' => trim($_POST['policies']),
                'is_available' => isset($_POST['is_available']) ? 1 : 0
            ];

            // Handle images upload on edit (append to existing)
            $existingImages = [];
            if (!empty($product['images'])) {
                $decoded = json_decode($product['images'], true);
                if (is_array($decoded)) $existingImages = $decoded;
            }
            $newImages = [];
            if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
                $uploadDir = 'public/uploads/products';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }
                $maxFiles = 10;
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $count = min(count($_FILES['images']['name']), $maxFiles);
                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                    if ($_FILES['images']['size'][$i] > $maxSize) continue;
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $type = mime_content_type($tmpName);
                    if (!in_array($type, $allowedTypes, true)) continue;

                    // Definir a extensão exclusivamente pelo MIME type
                    $safeExt = 'jpg';
                    if ($type === 'image/png') {
                        $safeExt = 'png';
                    } elseif ($type === 'image/webp') {
                        $safeExt = 'webp';
                    } elseif ($type === 'image/jpeg') {
                        $safeExt = 'jpg';
                    }
                    $filename = uniqid('prod_', true) . '.' . $safeExt;
                    $destination = $uploadDir . '/' . $filename;
                    if (move_uploaded_file($tmpName, $destination)) {
                        $newImages[] = $destination; // caminho relativo sem barra inicial
                    }
                }
            }
            $allImages = array_values(array_unique(array_merge($existingImages, $newImages)));
            if (!empty($allImages)) {
                $data['images'] = json_encode($allImages, JSON_UNESCAPED_SLASHES);
            }
            
            if ($this->productModel->update($id, $data)) {
                $_SESSION['success'] = 'Produto atualizado com sucesso!';
                header('Location: /dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao atualizar produto.';
            }
        }
        
        // Get categories for the form
        $categoryModel = new Category();
        $categories = $categoryModel->findAll();
        
        $this->view('dashboard/edit-product', [
            'title' => 'Editar Produto - Play4You',
            'product' => $product,
            'categories' => $categories
        ]);
    }
    
    public function deleteProduct($id) {
        $this->requireAuth();
        
        $product = $this->productModel->find($id);
        
        if (!$product || $product['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Produto não encontrado.';
        } else {
            if ($this->productModel->delete($id)) {
                $_SESSION['success'] = 'Produto removido com sucesso!';
            } else {
                // Se não conseguiu remover (provável FK em rentals), desativar o produto
                $this->productModel->update($id, ['is_available' => 0]);
                $_SESSION['error'] = 'Produto possui aluguéis vinculados e não pode ser removido. Ele foi marcado como indisponível.';
            }
        }
        
        header('Location: /dashboard');
        exit;
    }
    
    private function getUserStats($userId) {
        // Get total products
        $totalProducts = $this->productModel->countUserProducts($userId);
        
        // Get total rentals
        $totalRentals = $this->rentalModel->countOwnerRentals($userId);
        
        // Get total earnings
        $totalEarnings = $this->rentalModel->getTotalEarnings($userId);
        
        // Get active rentals
        $activeRentals = $this->rentalModel->countActiveRentals($userId);
        
        return [
            'total_products' => $totalProducts,
            'total_rentals' => $totalRentals,
            'total_earnings' => $totalEarnings,
            'active_rentals' => $activeRentals
        ];
    }
}
?>
