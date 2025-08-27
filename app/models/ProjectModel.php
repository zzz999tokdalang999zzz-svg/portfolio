<?php
class ProjectModel {
    private $db;

    public function __construct() {
        require_once(__DIR__ . '/../../config/config.php');
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getAll() {
        try {
            $stmt = $this->db->prepare("SELECT DISTINCT * FROM projects ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO projects (ten_duan, noidung, image) VALUES (:ten_duan, :noidung, :image)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':ten_duan' => $data['ten_duan'],
                ':noidung' => $data['noidung'],
                ':image' => $data['image']
            ]);
        } catch (PDOException $e) {
            error_log("Error creating project: " . $e->getMessage());
            return false;
        }
    }

    public function update($data) {
        try {
            $sql = "UPDATE projects SET ten_duan = :ten_duan, noidung = :noidung, image = :image WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $data['id'],
                ':ten_duan' => $data['ten_duan'],
                ':noidung' => $data['noidung'],
                ':image' => $data['image']
            ]);
        } catch (PDOException $e) {
            error_log("Error updating project: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM projects WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting project: " . $e->getMessage());
            return false;
        }
    }

    public function resetAutoIncrement() {
        try {
            // 1. Lấy tất cả dữ liệu hiện tại theo thứ tự ID
            $stmt = $this->db->query("SELECT * FROM projects ORDER BY id ASC");
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 2. Xóa toàn bộ dữ liệu và reset auto increment
            $this->db->exec("TRUNCATE TABLE projects");
            
            // 3. Thêm lại dữ liệu
            $stmt = $this->db->prepare("INSERT INTO projects (ten_duan, noidung, image) VALUES (:ten_duan, :noidung, :image)");
            
            foreach ($projects as $project) {
                $stmt->execute([
                    ':ten_duan' => $project['ten_duan'],
                    ':noidung' => $project['noidung'],
                    ':image' => $project['image']
                ]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error resetting auto increment: " . $e->getMessage());
            $_SESSION['error_message'] = "Lỗi khi reset ID: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Update image URL và public_id cho project
     */
    public function updateImage($projectId, $imageUrl, $publicId) {
        try {
            $sql = "UPDATE projects SET image = :image_url, cloudinary_public_id = :public_id WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $projectId,
                ':image_url' => $imageUrl,
                ':public_id' => $publicId
            ]);
        } catch (PDOException $e) {
            error_log("Error updating project image: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove image từ project
     */
    public function removeImage($projectId) {
        try {
            $sql = "UPDATE projects SET image = NULL, cloudinary_public_id = NULL WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $projectId]);
        } catch (PDOException $e) {
            error_log("Error removing project image: " . $e->getMessage());
            return false;
        }
    }
}
