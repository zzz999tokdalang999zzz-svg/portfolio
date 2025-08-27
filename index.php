<?php
// Load helper functions
require_once 'helpers/path.php';
require_once 'helpers/lang.php';

// Load database configuration
require 'config/config.php';

// Create database connection
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$controller = $_GET['controller'] ?? 'home';
$id = $_GET['id'] ?? null;

require_once 'app/models/ProjectModel.php';
require_once 'app/controllers/ProjectController.php';


switch ($controller) {
    case 'home':
        require_once 'app/controllers/PageController.php';
        $ctrl = new HomeController($conn);
        $ctrl->index();
        break;

    case 'contact':
        require_once 'app/controllers/MailController.php';
        $ctrl = new MailController();
        $action = $_GET['action'] ?? 'form';
        
        if ($action === 'send') {
            $ctrl->send();
        } else {
            $ctrl->showForm();
        }
        break;
        
    case 'about':
        require_once 'app/controllers/PageController.php';
        $ctrl = new AboutController();
        $ctrl->getText();
        break;

    case 'admin':
        require_once 'app/controllers/AdminController.php';
        $ctrl = new AdminController();
        $action = $_GET['action'] ?? 'dashboard';
        
        switch($action) {
            case 'dashboard':
                $ctrl->dashboard();
                break;
            case 'create':
                $ctrl->create();
                break;
            case 'edit':
                $ctrl->edit();
                break;
            case 'delete':
                $ctrl->delete();
                break;
            case 'resetIds':
                $ctrl->resetIds();
                break;
            default:
                $ctrl->dashboard();
                break;
        }
        break;

    case 'auth':
        require_once 'app/controllers/AuthController.php';
        $ctrl = new AuthController();
        $action = $_GET['action'] ?? 'showLogin';
        
        if ($action === 'login') {
            $ctrl->login();
        } else {
            // Hiển thị form login
            include 'app/views/admin/login.php';
        }
        break;

    case 'project':
        require_once 'app/controllers/ProjectController.php';
        $ctrl = new ProjectController();
        $ctrl->index();
        break; 
    case 'show':
        require_once 'app/controllers/ProjectController.php';
        $ctrl = new ProjectController();
        if ($id) {
            $ctrl->show($id);
        } else {
            echo "Không tìm thấy Dự Án";
        }
        break;
        
    case 'upload-image':
        require_once 'app/controllers/ProjectController.php';
        $ctrl = new ProjectController();
        $ctrl->uploadImage();
        break;
        
    case 'delete-image':
        require_once 'app/controllers/ProjectController.php';
        $ctrl = new ProjectController();
        $ctrl->deleteImage();
        break;
        
    default:
        echo "404 - Controller không tìm thấy!";
        break;
}

?>