<?php
class Order {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM tblorder");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => "Failed to fetch orders: " . $e->getMessage());
        }
    }

    public function placeOrder($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO tblorder (productID, shoppingCartID, userID) VALUES (?, ?, ?)");
            $stmt->execute([$data['productID'], $data['shoppingCartID'], $data['userID']]);
            return array("success" => "Order placed successfully. ID: " . $this->conn->lastInsertId());
        } catch (PDOException $e) {
            return array("error" => "Failed to place order: " . $e->getMessage());
        }
    }

    public function checkout($shoppingCartID) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM shopping_cart WHERE shoppingCartID = ?");
            $stmt->execute([$shoppingCartID]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cartItem) {
                return array("error" => "Shopping cart item not found.");
            }

            $total = ($cartItem['ammount'] * $cartItem['quntity']) + $cartItem['shippingCost'];

            $stmt = $this->conn->prepare("INSERT INTO tblorder (productname, quntity, totalPrice, userID) VALUES ( ?, ?, ?, ?)");
            $stmt->execute([$cartItem['productname'], $cartItem['quntity'], $total, $cartItem['userID']]);

            if ($stmt->rowCount() > 0) {
                $this->delete($shoppingCartID);
                return array("success" => "Checkout successful.");
            } else {
                return array("error" => "Failed to checkout.");
            }
        } catch (PDOException $e) {
            return array("error" => "Failed to checkout: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE tblorder SET productID = ?, shoppingCartID = ?, userID = ? WHERE orderID = ?");
            $stmt->execute([$data['productID'], $data['shoppingCartID'], $data['userID'], $id]);
            return array("success" => "Order updated successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to update order: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM tblorder WHERE orderID = ?");
            $stmt->execute([$id]);
            return array("success" => "Order deleted successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to delete order: " . $e->getMessage());
        }
    }
}
?>
