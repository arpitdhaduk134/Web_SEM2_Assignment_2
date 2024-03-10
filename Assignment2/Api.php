<?php
require 'connection/connection.php';
require 'tables/product.php';
require 'tables/user.php';
require 'tables/comments.php';
require 'tables/shopping_cart.php';
require 'tables/order.php';

$db = new Database();
$conn = $db->getConnection();




//Product API
$product = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_products') {
    header('Content-Type: application/json');
    echo json_encode($product->getAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_product') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $product->add($data);
    echo json_encode(array("id" => $id));
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_product') {
    parse_str(file_get_contents("php://input"), $data);
    $product->update($data['id'], $data);
    echo json_encode(array("message" => "Product updated."));
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_product') {
    parse_str(file_get_contents("php://input"), $data);
    $product->delete($data['id']);
    echo json_encode(array("message" => "Product deleted."));
}





// User API
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_users') {
    header('Content-Type: application/json');
    echo json_encode($user->getAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_user') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $user->add($data);
    echo json_encode(array("id" => $id));
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_user') {
    parse_str(file_get_contents("php://input"), $data);
    $user->update($data['id'], $data);
    echo json_encode(array("message" => "User updated."));
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_user') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "User ID is required."));
        exit;
    }

    $user_id = $_GET['id'];
    $user->delete($userID);
    echo json_encode(array("message" => "User is deleted."));
}




// Comment API
$comment = new Comment($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_comments') {
    header('Content-Type: application/json');
    echo json_encode($comment->getAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_comment') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $comment->add($data);
    echo json_encode(array("id" => $id));
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_comment') {
    parse_str(file_get_contents("php://input"), $data);
    $comment->update($data['id'], $data);
    echo json_encode(array("message" => "Comment updated."));
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_comment') {
    parse_str(file_get_contents("php://input"), $data);
    $comment->delete($data['id']);
    echo json_encode(array("message" => "Comment deleted."));
}




// Cart API
$cart = new Cart($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_cart') {
    header('Content-Type: application/json');
    echo json_encode($cart->getAll());
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_cart') {
    parse_str(file_get_contents("php://input"), $data);
    $cart->update($data['id'], $data);
    echo json_encode(array("message" => "Cart updated."));
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_from_cart') {
    parse_str(file_get_contents("php://input"), $data);
    $cart->delete($data['id']);
    echo json_encode(array("message" => "Item removed from cart."));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_to_sc') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['productID'], $data['quantity'], $data['userID'])) {
        $id = $cart->add_to_sc($data['productID'], $data['quantity'], $data['userID']);
        echo json_encode(array("id" => $id));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Product ID, quantity, and user ID are required."));
    }
}




// Order API
$order = new Order($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_orders') {
    header('Content-Type: application/json');
    echo json_encode($order->getAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'place_order') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $order->placeOrder($data);
    echo json_encode(array("id" => $id));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'checkout') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['shoppingCartID'])) {
        $orderAdded = $order->checkout($data['shoppingCartID']);
        if ($orderAdded) {
            echo json_encode(array("message" => "Order added successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Error adding order."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Shopping cart ID is required."));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_order') {
    parse_str(file_get_contents("php://input"), $data);
    $order->update($data['id'], $data);
    echo json_encode(array("message" => "Order updated."));
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'cancel_order') {
    parse_str(file_get_contents("php://input"), $data);
    $order->delete($data['id']);
    echo json_encode(array("message" => "Order cancelled."));
}
?>