<?php
require_once(__DIR__ . '/../models/ProjectModel.php');
require_once(__DIR__ . '/../models/CloudinaryService.php');

class ProjectController {
    private $model;
    private $cloudinary;

    public function __construct() {
        $this->model = new ProjectModel();
        $this->cloudinary = new CloudinaryService();
    }

    public function index() {
        $projects = $this->model->getAll();
        include(__DIR__ . '/../views/pages/home.php');
    }

    public function show($id) {
        $project = $this->model->getById($id);
        $content = 'app/views/projects/project.php';
        include 'app/views/layouts/app.php';
    }
    
    /**
     * Upload project image
     */
    public function uploadImage() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        if (!isset($_FILES['image']) || !isset($_POST['project_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $file = $_FILES['image'];
        $projectId = $_POST['project_id'];
        
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file type']);
            return;
        }
        
        // Upload to Cloudinary
        $result = $this->cloudinary->uploadProjectImage($file, $projectId);
        
        if ($result['success']) {
            // Update project với URL mới
            $this->model->updateImage($projectId, $result['url'], $result['public_id']);
            
            echo json_encode([
                'success' => true,
                'url' => $result['url'],
                'thumbnail' => $this->cloudinary->getProjectThumbnail($result['public_id'])
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
        }
    }
    
    /**
     * Delete project image
     */
    public function deleteImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['public_id']) || !isset($input['project_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $publicId = $input['public_id'];
        $projectId = $input['project_id'];
        
        // Delete from Cloudinary
        $deleted = $this->cloudinary->deleteImage($publicId);
        
        if ($deleted) {
            // Update project để xóa image URL
            $this->model->removeImage($projectId);
            
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete image']);
        }
    }
}
