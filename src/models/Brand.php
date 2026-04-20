<?php
// ============================================================
// src/models/Brand.php
// ============================================================
class Brand {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name): bool {
        $stmt = $this->db->prepare("INSERT INTO brands (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
