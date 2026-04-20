<?php
// ============================================================
// src/models/Car.php
// ============================================================
class Car {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ---- PUBLIC LISTING ----
    public function getApproved(array $filters = [], int $page = 1): array {
        $where = ["c.status = 'approved'"];
        $params = [];

        if (!empty($filters['brand_id'])) {
            $where[] = "c.brand_id = ?";
            $params[] = (int)$filters['brand_id'];
        }
        if (!empty($filters['year'])) {
            $where[] = "c.year = ?";
            $params[] = (int)$filters['year'];
        }
        if (!empty($filters['min_price'])) {
            $where[] = "c.price >= ?";
            $params[] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "c.price <= ?";
            $params[] = (float)$filters['max_price'];
        }
        if (!empty($filters['keyword'])) {
            $where[] = "(c.title LIKE ? OR c.description LIKE ?)";
            $kw = '%' . $filters['keyword'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }

        $whereStr = implode(' AND ', $where);
        $offset = ($page - 1) * ITEMS_PER_PAGE;

        $sql = "SELECT c.*, b.name AS brand_name, u.name AS seller_name, u.phone AS seller_phone, u.email AS seller_email,
                (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
                FROM cars c
                JOIN brands b ON c.brand_id = b.id
                JOIN users u ON c.user_id = u.id
                WHERE $whereStr
                ORDER BY c.created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $params[] = ITEMS_PER_PAGE;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countApproved(array $filters = []): int {
        $where = ["status = 'approved'"];
        $params = [];

        if (!empty($filters['brand_id'])) { $where[] = "brand_id = ?"; $params[] = (int)$filters['brand_id']; }
        if (!empty($filters['year']))     { $where[] = "year = ?";     $params[] = (int)$filters['year']; }
        if (!empty($filters['min_price'])){ $where[] = "price >= ?";   $params[] = (float)$filters['min_price']; }
        if (!empty($filters['max_price'])){ $where[] = "price <= ?";   $params[] = (float)$filters['max_price']; }
        if (!empty($filters['keyword'])) {
            $where[] = "(title LIKE ? OR description LIKE ?)";
            $kw = '%' . $filters['keyword'] . '%';
            $params[] = $kw; $params[] = $kw;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars WHERE " . implode(' AND ', $where));
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // ---- DETAIL ----
    public function findById(int $id): ?array {
        $sql = "SELECT c.*, b.name AS brand_name, u.name AS seller_name,
                u.phone AS seller_phone, u.email AS seller_email
                FROM cars c
                JOIN brands b ON c.brand_id = b.id
                JOIN users u ON c.user_id = u.id
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getImages(int $carId): array {
        $stmt = $this->db->prepare("SELECT * FROM car_images WHERE car_id = ? ORDER BY is_primary DESC");
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    public function incrementViews(int $id): void {
        $this->db->prepare("UPDATE cars SET views = views + 1 WHERE id = ?")->execute([$id]);
    }

    // ---- USER CARS ----
    public function getByUser(int $userId, int $page = 1): array {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $sql = "SELECT c.*, b.name AS brand_name,
                (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
                FROM cars c JOIN brands b ON c.brand_id = b.id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, ITEMS_PER_PAGE, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByUser(int $userId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    // ---- ADMIN ----
    public function getAllAdmin(int $page = 1, string $status = ''): array {
        $where = $status ? "WHERE c.status = ?" : "";
        $params = $status ? [$status] : [];
        $offset = ($page - 1) * ITEMS_PER_PAGE;

        $sql = "SELECT c.*, b.name AS brand_name, u.name AS seller_name,
                (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
                FROM cars c JOIN brands b ON c.brand_id = b.id JOIN users u ON c.user_id = u.id
                $where ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $params[] = ITEMS_PER_PAGE;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAllAdmin(string $status = ''): int {
        if ($status) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars WHERE status = ?");
            $stmt->execute([$status]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM cars");
        }
        return (int)$stmt->fetchColumn();
    }

    // ---- CRUD ----
    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO cars (user_id, brand_id, title, price, year, mileage, fuel_type, transmission, color, description, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $ok = $stmt->execute([
            $data['user_id'],
            $data['brand_id'],
            $data['title'],
            $data['price'],
            $data['year'],
            $data['mileage'] ?? 0,
            $data['fuel_type'] ?? 'xang',
            $data['transmission'] ?? 'tu_dong',
            $data['color'] ?? null,
            $data['description'] ?? null,
            isAdmin() ? 'approved' : 'pending',
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function addImage(int $carId, string $imagePath, bool $isPrimary = false): void {
        $stmt = $this->db->prepare("INSERT INTO car_images (car_id, image_path, is_primary) VALUES (?, ?, ?)");
        $stmt->execute([$carId, $imagePath, $isPrimary ? 1 : 0]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE cars SET brand_id=?, title=?, price=?, year=?, mileage=?, fuel_type=?, transmission=?, color=?, description=? WHERE id=?"
        );
        return $stmt->execute([
            $data['brand_id'], $data['title'], $data['price'],
            $data['year'], $data['mileage'] ?? 0,
            $data['fuel_type'] ?? 'xang', $data['transmission'] ?? 'tu_dong',
            $data['color'] ?? null, $data['description'] ?? null, $id,
        ]);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE cars SET status=? WHERE id=?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM cars WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isOwner(int $carId, int $userId): bool {
        $stmt = $this->db->prepare("SELECT id FROM cars WHERE id = ? AND user_id = ?");
        $stmt->execute([$carId, $userId]);
        return (bool)$stmt->fetch();
    }

    // ---- STATS ----
    public function countByStatus(): array {
        $result = [];
        $rows = $this->db->query("SELECT status, COUNT(*) as cnt FROM cars GROUP BY status")->fetchAll();
        foreach ($rows as $row) $result[$row['status']] = $row['cnt'];
        return $result;
    }
}
