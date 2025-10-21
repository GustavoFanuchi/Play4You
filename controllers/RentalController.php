<?php
require_once 'models/Rental.php';
require_once 'models/Product.php';
require_once 'models/User.php';

class RentalController extends Controller {
    private $rentalModel;
    private $productModel;
    private $userModel;
    
    public function __construct() {
        $this->rentalModel = new Rental();
        $this->productModel = new Product();
        $this->userModel = new User();
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = intval($_POST['product_id']);
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $deliveryMethod = $_POST['delivery_method'];
            $deliveryAddress = $_POST['delivery_address'] ?? '';
            
            // Validate dates
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $today = new DateTime();
            
            $errors = [];
            
            if ($start < $today) {
                $errors[] = 'Data de início não pode ser no passado.';
            }
            
            if ($end <= $start) {
                $errors[] = 'Data de fim deve ser posterior à data de início.';
            }
            
            // Get product details
            $product = $this->productModel->getProductDetails($productId);
            if (!$product) {
                $errors[] = 'Produto não encontrado.';
            }
            
            if (!$product['is_available']) {
                $errors[] = 'Produto não está disponível.';
            }
            
            // Check if user is not the owner
            if ($product['user_id'] == $_SESSION['user_id']) {
                $errors[] = 'Você não pode alugar seu próprio produto.';
            }
            
            // Check availability for the selected dates
            if (!$this->rentalModel->isProductAvailable($productId, $startDate, $endDate)) {
                $errors[] = 'Produto não está disponível nas datas selecionadas.';
            }
            
            if ($deliveryMethod === 'delivery' && empty($deliveryAddress)) {
                $errors[] = 'Endereço de entrega é obrigatório.';
            }
            
            if (empty($errors)) {
                // Calculate rental details
                $totalDays = $start->diff($end)->days + 1;
                $dailyRate = $product['daily_price'];
                $subtotal = $totalDays * $dailyRate;
                $serviceFee = $subtotal * 0.10; // 10% service fee
                $insuranceFee = $subtotal * 0.05; // 5% insurance fee
                $deliveryFee = ($deliveryMethod === 'delivery') ? 25.00 : 0;
                $totalAmount = $subtotal + $serviceFee + $insuranceFee + $deliveryFee;
                $depositAmount = $product['deposit_amount'] ?? ($dailyRate * 2);
                
                $rentalData = [
                    'product_id' => $productId,
                    'renter_id' => $_SESSION['user_id'],
                    'owner_id' => $product['user_id'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_days' => $totalDays,
                    'daily_rate' => $dailyRate,
                    'subtotal' => $subtotal,
                    'service_fee' => $serviceFee,
                    'insurance_fee' => $insuranceFee,
                    'total_amount' => $totalAmount,
                    'deposit_amount' => $depositAmount,
                    'delivery_method' => $deliveryMethod,
                    'delivery_address' => $deliveryAddress,
                    'status' => 'pending'
                ];
                
                $rentalId = $this->rentalModel->create($rentalData);
                
                if ($rentalId) {
                    $this->redirect('/rental/confirmation?id=' . $rentalId);
                } else {
                    $errors[] = 'Erro ao processar reserva. Tente novamente.';
                }
            }
        }
        
        // If GET request or errors, show booking form
        $productId = $_GET['product_id'] ?? $_POST['product_id'] ?? null;
        if (!$productId) {
            $this->redirect('/catalog');
            return;
        }
        
        $product = $this->productModel->getProductDetails($productId);
        if (!$product) {
            $this->redirect('/catalog');
            return;
        }
        
        $this->view('rentals/create', [
            'title' => 'Reservar ' . htmlspecialchars($product['title']) . ' - Play4You',
            'product' => $product,
            'errors' => $errors ?? [],
            'formData' => $_POST ?? []
        ]);
    }
    
    public function confirmation($rentalId = null) {
        $this->requireAuth();
        
        $rentalId = $rentalId ?? intval($_GET['id'] ?? 0);
        if (!$rentalId) { $this->redirect('/rentals'); return; }

        $rental = $this->rentalModel->getRentalDetails($rentalId);
        
        if (!$rental || $rental['renter_id'] != $_SESSION['user_id']) {
            $this->redirect('/');
            return;
        }
        
        $this->view('rentals/confirmation', [
            'title' => 'Confirmação de Reserva - Play4You',
            'rental' => $rental
        ]);
    }
    
    public function myRentals() {
        $this->requireAuth();
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $rentals = $this->rentalModel->getUserRentals($_SESSION['user_id'], $limit, $offset);
        $totalRentals = $this->rentalModel->countUserRentals($_SESSION['user_id']);
        $totalPages = ceil($totalRentals / $limit);
        
        $this->view('rentals/my-rentals', [
            'title' => 'Meus Aluguéis - Play4You',
            'rentals' => $rentals,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
    
    public function show($rentalId) {
        $this->requireAuth();
        
        $rental = $this->rentalModel->getRentalDetails($rentalId);
        
        if (!$rental || ($rental['renter_id'] != $_SESSION['user_id'] && $rental['owner_id'] != $_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }
        
        $this->view('rentals/show', [
            'title' => 'Detalhes do Aluguel - Play4You',
            'rental' => $rental
        ]);
    }
    
    public function cancel($rentalId) {
        $this->requireAuth();
        
        $rental = $this->rentalModel->find($rentalId);
        
        if (!$rental || $rental['renter_id'] != $_SESSION['user_id']) {
            $this->redirect('/');
            return;
        }
        
        // Check if cancellation is allowed (24h before start date)
        $startDate = new DateTime($rental['start_date']);
        $now = new DateTime();
        $hoursDiff = $now->diff($startDate)->h + ($now->diff($startDate)->days * 24);
        
        if ($hoursDiff < 24) {
            $_SESSION['error'] = 'Cancelamento só é permitido até 24h antes do início do aluguel.';
            $this->redirect("/rental/{$rentalId}");
            return;
        }
        
        if ($this->rentalModel->update($rentalId, ['status' => 'cancelled'])) {
            $_SESSION['success'] = 'Aluguel cancelado com sucesso.';
        } else {
            $_SESSION['error'] = 'Erro ao cancelar aluguel.';
        }
        
        $this->redirect("/rental/{$rentalId}");
    }

    public function checkout() {
        $this->requireAuth();

        $rentalId = intval($_GET['id'] ?? 0);
        if (!$rentalId) { $this->redirect('/rentals'); return; }

        $rental = $this->rentalModel->getRentalDetails($rentalId);
        if (!$rental || $rental['renter_id'] != $_SESSION['user_id']) { $this->redirect('/rentals'); return; }

        if ($rental['payment_status'] === 'paid') { $this->redirect('/rental/show?id=' . $rentalId); return; }

        $this->view('rentals/checkout', [
            'title' => 'Pagamento do Aluguel - Play4You',
            'rental' => $rental
        ]);
    }

    public function pay() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); return; }

        $rentalId = intval($_POST['rental_id'] ?? 0);
        $paymentMethod = $_POST['payment_method'] ?? 'pix';

        $rental = $this->rentalModel->getRentalDetails($rentalId);
        if (!$rental || $rental['renter_id'] != $_SESSION['user_id']) { http_response_code(403); echo 'Acesso negado'; return; }

        // Simula pagamento aprovado
        $updated = $this->rentalModel->update($rentalId, [
            'payment_status' => 'paid',
            'status' => 'confirmed'
        ]);

        if ($updated) {
            $_SESSION['success'] = 'Pagamento confirmado!';
            $this->redirect('/rental/confirmation?id=' . $rentalId);
        } else {
            $_SESSION['error'] = 'Falha ao processar pagamento.';
            $this->redirect('/rental/checkout?id=' . $rentalId);
        }
    }
}
?>
