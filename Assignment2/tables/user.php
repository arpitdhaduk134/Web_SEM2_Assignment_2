<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM user");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => "Failed to fetch users: " . $e->getMessage());
        }
    }

    public function add($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO user (name, email, password, address, orderID) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['email'], $data['password'], $data['address'], $data['orderID']]);
            return array("success" => "User added successfully. ID: " . $this->conn->lastInsertId());
        } catch (PDOException $e) {
            return array("error" => "Failed to add user: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE user SET name = ?, email = ?, password = ?, address = ?, orderID = ? WHERE userID = ?");
            $stmt->execute([$data['name'], $data['email'], $data['password'], $data['address'], $data['orderID'], $id]);
            return array("success" => "User updated successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to update user: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user WHERE userID = ?");
            $stmt->execute([$id]);
            return array("success" => "User deleted successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to delete user: " . $e->getMessage());
        }
    }
}
?>
