<?php
class AboutModel {
    private $db;

    public function __construct() {
        require_once(__DIR__ . '/../../config/config.php');
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getProfileInfo() {
        try {
            $stmt = $this->db->prepare("SELECT id, hoten, bio, image, hashedpassword FROM profile");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function verifyLogin($password) {
        try {
            $stmt = $this->db->prepare("SELECT id, hoten, hashedpassword FROM profile LIMIT 1");
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['hashedpassword'])) {
                return $user;
            }
            return false;
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getText() {
        $profile = $this->getProfileInfo();
        if (isset($profile['error'])) {
            return "Error loading profile information.";
        }
        return $profile;
    }
}
