<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM product");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => "Failed to fetch products: " . $e->getMessage());
        }
    }

    public function add($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO product (productname, price, description, image, shippingCost) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['productname'], $data['price'], $data['description'], $data['image'], $data['shippingCost']]);
            return array("success" => "Product added successfully. ID: " . $this->conn->lastInsertId());
        } catch (PDOException $e) {
            return array("error" => "Failed to add product: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE product SET productname = ?, price = ?, description = ?, image = ?, shippingCost = ?  WHERE productID = ?");
            $stmt->execute([$data['productname'], $data['price'], $data['description'], $data['image'], $data['shippingCost'], $id]);
            return array("success" => "Product updated successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to update product: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM product WHERE productID = ?");
            $stmt->execute([$id]);
            return array("success" => "Product deleted successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to delete product: " . $e->getMessage());
        }
    }
}
?>
