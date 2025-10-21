<?php
require_once 'models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                $error = 'Por favor, preencha todos os campos.';
            } else {
                $user = $this->userModel->findByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    $this->redirect('/');
                } else {
                    $error = 'Email ou senha incorretos.';
                }
            }
        }
        
        $this->view('auth/login', [
            'title' => 'Login - Play4You',
            'error' => $error ?? null
        ]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            $phone = trim($_POST['phone']);
            $city = trim($_POST['city']);
            $state = trim($_POST['state']);
            
            $errors = [];
            
            // Validations
            if (empty($name)) $errors[] = 'Nome é obrigatório.';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email válido é obrigatório.';
            }
            if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
            if ($password !== $confirmPassword) $errors[] = 'Senhas não coincidem.';
            
            // Phone validation
            if (!empty($phone) && !$this->validatePhone($phone)) {
                $errors[] = 'Telefone deve ter o formato (XX) XXXXX-XXXX.';
            }
            
            // Check if email already exists
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Este email já está cadastrado.';
            }
            
            if (empty($errors)) {
                $userData = [
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'phone' => $phone,
                    'city' => $city,
                    'state' => $state
                ];
                
                $userId = $this->userModel->create($userData);
                
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    
                    $this->redirect('/');
                } else {
                    $errors[] = 'Erro ao criar conta. Tente novamente.';
                }
            }
        }
        
        $this->view('auth/register', [
            'title' => 'Cadastro - Play4You',
            'errors' => $errors ?? [],
            'formData' => $_POST ?? []
        ]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
    
    private function validatePhone($phone) {
        // Remove all non-numeric characters
        $cleanPhone = preg_replace('/\D/', '', $phone);
        // Check if it has exactly 10 or 11 digits
        return strlen($cleanPhone) === 10 || strlen($cleanPhone) === 11;
    }
}
?>
