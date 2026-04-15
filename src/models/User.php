<?php
// ============================================================
// src/models/User.php
// ============================================================
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(int $page = 1): array {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, ITEMS_PER_PAGE, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['phone'] ?? null,
            $data['role'] ?? 'user',
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET name=?, phone=?, role=?, is_active=? WHERE id=?"
        );
        return $stmt->execute([
            $data['name'],
            $data['phone'] ?? null,
            $data['role'] ?? 'user',
            $data['is_active'] ?? 1,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        return $stmt->execute([$id]);
    }

    public function toggleActive(int $id): bool {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function emailExists(string $email, int $excludeId = 0): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excludeId]);
        return (bool)$stmt->fetch();
    }
}
