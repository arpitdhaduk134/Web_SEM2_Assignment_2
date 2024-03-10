<?php
class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM shopping_cart");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => "Failed to fetch shopping cart items: " . $e->getMessage());
        }
    }

    public function add_to_sc($productID, $quantity, $userID) {
        try {
            if (!isset($productID, $quantity, $userID)) {
                http_response_code(400);
                echo json_encode(array("message" => "Product ID, quantity, and user ID are required."));
                exit;
            }
        
            $stmt = $this->conn->prepare("SELECT * FROM product WHERE productID = ?");
            $stmt->execute([$productID]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                http_response_code(404);
                echo json_encode(array("message" => "Product not found."));
                exit;
            }
        
            $stmt = $this->conn->prepare("INSERT INTO shopping_cart (productname, ammount, userID, quntity, shippingCost) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$product['productname'], $product['price'], $userID, $quantity, $product['shippingCost']]);
            return array("success" => "Product added to shopping cart successfully. ID: " . $this->conn->lastInsertId());
        } catch (PDOException $e) {
            return array("error" => "Failed to add product to shopping cart: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE shopping_cart SET productID = ?, userID = ?, quantity = ? WHERE shoppingCartID = ?");
            $stmt->execute([$data['productID'], $data['user'], $data['quantity'], $id]);
            return array("success" => "Shopping cart item updated successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to update shopping cart item: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM shopping_cart WHERE shoppingCartID = ?");
            $stmt->execute([$id]);
            return array("success" => "Shopping cart item deleted successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to delete shopping cart item: " . $e->getMessage());
        }
    }
}
?>
