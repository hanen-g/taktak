<?php
include("connection/connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $rating = intval($_POST['rating']);
    $user_id = $_SESSION['user_id'];

    if ($order_id && $rating >= 1 && $rating <= 5) {
        $stmt = $db->prepare("UPDATE users_orders SET user_rating = ? WHERE o_id = ? AND u_id = ?");
        $stmt->bind_param("iii", $rating, $order_id, $user_id);
        $stmt->execute();
        echo "success";
    } else {
        http_response_code(400);
        echo "Invalid data";
    }
}
?>
