<?php
class Comment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM comments");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array("error" => "Failed to fetch comments: " . $e->getMessage());
        }
    }

    public function add($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO comments (productID, userID, ratings, commentImage, commentText) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['productID'], $data['userID'], $data['ratings'], $data['commentImage'], $data['commentText']]);
            return array("success" => "Comment added successfully. ID: " . $this->conn->lastInsertId());
        } catch (PDOException $e) {
            return array("error" => "Failed to add comment: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE comments SET productID = ?, userID = ?, ratings = ?, commentImage = ?, commentText = ? WHERE commentID = ?");
            $stmt->execute([$data['productID'], $data['userID'], $data['ratings'], $data['commentImage'], $data['commentText'], $id]);
            return array("success" => "Comment updated successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to update comment: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM comments WHERE commentID = ?");
            $stmt->execute([$id]);
            return array("success" => "Comment deleted successfully.");
        } catch (PDOException $e) {
            return array("error" => "Failed to delete comment: " . $e->getMessage());
        }
    }
}
?>
