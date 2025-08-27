<?php
class HomeModel {
    private $db;

    public function __construct() {
        require_once(__DIR__ . '/../../config/config.php');
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getAllProjects() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}