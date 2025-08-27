<?php
require_once(__DIR__ . '/../models/ProjectModel.php');
require_once(__DIR__ . '/../models/CloudinaryService.php');
require_once(__DIR__ . '/../../helpers/path.php');

class AdminController {
    private $projectModel;
    private $cloudinaryService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập cho tất cả các action trừ login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . getActionUrl('auth'));
            exit();
        }

        $this->projectModel = new ProjectModel();
        $this->cloudinaryService = new CloudinaryService();
    }

    public function dashboard() {
        $projects = $this->projectModel->getAll();
        require_once(__DIR__ . '/../views/admin/dashboard.php');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_duan = $_POST['ten_duan'] ?? '';
            $noidung = $_POST['noidung'] ?? '';
            $image_type = $_POST['image_type'] ?? 'file';
            $image = '';

            // Xử lý ảnh theo loại được chọn
            if ($image_type === 'url') {
                // Xử lý URL
                $image_url = $_POST['image_url'] ?? '';
                if (!empty($image_url) && filter_var($image_url, FILTER_VALIDATE_URL)) {
                    $image = $image_url;
                }
            } elseif ($image_type === 'cloudinary') {
                // Xử lý Cloudinary URL
                $cloudinary_url = $_POST['cloudinary_url'] ?? '';
                if (!empty($cloudinary_url)) {
                    $image = $cloudinary_url;
                }
            } else {
                // Xử lý upload hình ảnh thông thường
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'public/image/projects/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $image = $uploadFile;
                    }
                }
            }

            if ($this->projectModel->create([
                'ten_duan' => $ten_duan,
                'noidung' => $noidung,
                'image' => $image
            ])) {
                header('Location: ' . getActionUrl('admin', 'dashboard') . '');
                exit();
            }
        }

        require_once(__DIR__ . '/../views/admin/create.php');
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . getActionUrl('admin', 'dashboard') . '');
            exit();
        }

        $project = $this->projectModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_duan = $_POST['ten_duan'] ?? $project['ten_duan'];
            $noidung = $_POST['noidung'] ?? $project['noidung'];
            $image_type = $_POST['image_type'] ?? 'file';
            $image = $project['image'];

            // Xử lý ảnh theo loại được chọn
            if ($image_type === 'url') {
                // Xử lý URL
                $image_url = $_POST['image_url'] ?? '';
                if (!empty($image_url) && filter_var($image_url, FILTER_VALIDATE_URL)) {
                    // Xóa ảnh cũ nếu nó là file upload (không phải URL)
                    if ($project['image'] && strpos($project['image'], 'http') !== 0 && file_exists($project['image'])) {
                        unlink($project['image']);
                    }
                    $image = $image_url;
                }
            } elseif ($image_type === 'cloudinary') {
                // Xử lý Cloudinary URL
                $cloudinary_url = $_POST['cloudinary_url'] ?? '';
                if (!empty($cloudinary_url)) {
                    // TODO: Xóa ảnh cũ từ Cloudinary nếu cần
                    $image = $cloudinary_url;
                }
            } else {
                // Xử lý upload hình ảnh mới
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'public/image/projects/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                    $uploadFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        // Xóa ảnh cũ nếu tồn tại và là file upload
                        if ($project['image'] && strpos($project['image'], 'http') !== 0 && file_exists($project['image'])) {
                            unlink($project['image']);
                        }
                        $image = $uploadFile;
                    }
                }
            }

            if ($this->projectModel->update([
                'id' => $id,
                'ten_duan' => $ten_duan,
                'noidung' => $noidung,
                'image' => $image
            ])) {
                header('Location: ' . getActionUrl('admin', 'dashboard') . '');
                exit();
            }
        }

        require_once(__DIR__ . '/../views/admin/create.php');
    }

    public function resetIds() {
        try {
            if ($this->projectModel->resetAutoIncrement()) {
                $_SESSION['success_message'] = "Đã reset lại ID thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi reset ID!";
            }
        } catch (Exception $e) {
            error_log("Reset ID error: " . $e->getMessage());
            $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
        }
        header('Location: ' . getActionUrl('admin', 'dashboard') . '');
        exit();
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $project = $this->projectModel->getById($id);
            // Chỉ xóa file nếu là ảnh upload (không phải URL)
            if ($project && $project['image'] && strpos($project['image'], 'http') !== 0 && file_exists($project['image'])) {
                unlink($project['image']);
            }
            $this->projectModel->delete($id);
        }
        header('Location: ' . getActionUrl('admin', 'dashboard') . '');
        exit();
    }
}
