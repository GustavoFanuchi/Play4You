<?php
require_once 'models/User.php';

class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $this->requireAuth();

        $userId = $_SESSION['user_id'];
        $profile = $this->userModel->find($userId);

        $this->view('profile/index', [
            'title' => 'Meu Perfil - Play4You',
            'profile' => $profile
        ]);
    }

    public function update() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'zip_code' => trim($_POST['zip_code'] ?? '')
        ];

        // Basic validation
        $errors = [];
        if (empty($data['name'])) $errors[] = 'Nome é obrigatório.';
        
        // Phone validation
        if (!empty($data['phone']) && !$this->validatePhone($data['phone'])) {
            $errors[] = 'Telefone deve ter o formato (XX) XXXXX-XXXX.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            header('Location: /profile');
            exit;
        }

        $updated = $this->userModel->updateProfile($userId, $data);

        if ($updated) {
            $_SESSION['success'] = 'Perfil atualizado com sucesso!';
            $_SESSION['user_name'] = $data['name'];
        } else {
            $_SESSION['error'] = 'Nenhuma alteração realizada ou erro ao atualizar.';
        }

        header('Location: /profile');
        exit;
    }

    public function delete() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Simple confirmation token to avoid CSRF (basic)
        $confirm = $_POST['confirm'] ?? '';
        if ($confirm !== 'DELETE') {
            $_SESSION['error'] = 'Digite DELETE para confirmar a exclusão.';
            header('Location: /profile');
            exit;
        }

        // Delete user account
        $deleted = $this->userModel->delete($userId);

        if ($deleted) {
            session_destroy();
            header('Location: /');
            exit;
        } else {
            $_SESSION['error'] = 'Não foi possível excluir a conta.';
            header('Location: /profile');
            exit;
        }
    }
    
    private function validatePhone($phone) {
        // Remove all non-numeric characters
        $cleanPhone = preg_replace('/\D/', '', $phone);
        // Check if it has exactly 10 or 11 digits
        return strlen($cleanPhone) === 10 || strlen($cleanPhone) === 11;
    }
}
?>


