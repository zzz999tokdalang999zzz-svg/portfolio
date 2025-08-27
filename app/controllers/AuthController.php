<?php
require_once(__DIR__ . '/../models/AboutModel.php');
require_once(__DIR__ . '/../../helpers/path.php');

class AuthController {
    private $aboutModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->aboutModel = new AboutModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->redirectWithError('Invalid request method');
        }

        if (!isset($_POST['password'])) {
            return $this->redirectWithError('Password is required');
        }

        $password = $_POST['password'];
        $user = $this->aboutModel->verifyLogin($password);

        if ($user === false) {
            return $this->redirectWithError('Invalid password');
        }

        if (isset($user['error'])) {
            return $this->redirectWithError('System error occurred');
        }

        // Đăng nhập thành công
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['hoten'];
        
        // Chuyển hướng đến trang dashboard
        header('Location: ' . getActionUrl('admin', 'dashboard'));
        exit();
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . getActionUrl('auth'));
        exit();
    }

    private function redirectWithError($message) {
        $_SESSION['error'] = $message;
        header('Location: ' . getActionUrl('auth'));
        exit();
    }

    public function showLoginForm() {
        require_once(__DIR__ . '/../views/admin/login.php');
    }
}
