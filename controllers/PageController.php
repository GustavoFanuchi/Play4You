<?php
require_once 'models/Contact.php';

class PageController extends Controller {
    private $contactModel;
    
    public function __construct() {
        $this->contactModel = new Contact();
    }
    
    public function about() {
        $this->view('pages/about', [
            'title' => 'Sobre Nós - Play4You'
        ]);
    }
    
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            $errors = [];
            
            if (empty($name)) $errors[] = 'Nome é obrigatório.';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email válido é obrigatório.';
            }
            if (empty($subject)) $errors[] = 'Assunto é obrigatório.';
            if (empty($message)) $errors[] = 'Mensagem é obrigatória.';
            
            if (empty($errors)) {
                $contactData = [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                    'user_id' => $_SESSION['user_id'] ?? null
                ];
                
                if ($this->contactModel->create($contactData)) {
                    $_SESSION['success'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
                    $this->redirect('/contact');
                    return;
                } else {
                    $errors[] = 'Erro ao enviar mensagem. Tente novamente.';
                }
            }
        }
        
        $this->view('pages/contact', [
            'title' => 'Contato - Play4You',
            'errors' => $errors ?? [],
            'formData' => $_POST ?? []
        ]);
    }
    
    public function terms() {
        $this->view('pages/terms', [
            'title' => 'Termos de Uso - Play4You'
        ]);
    }
    
    public function privacy() {
        $this->view('pages/privacy', [
            'title' => 'Política de Privacidade - Play4You'
        ]);
    }
    
    public function howItWorks() {
        $this->view('pages/how-it-works', [
            'title' => 'Como Funciona - Play4You'
        ]);
    }
    
    public function safety() {
        $this->view('pages/safety', [
            'title' => 'Segurança - Play4You'
        ]);
    }
    
    public function faq() {
        $this->view('pages/faq', [
            'title' => 'Perguntas Frequentes - Play4You'
        ]);
    }
}
?>
