
<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    require 'database.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if(isset($data['id']) && isset($data['status'])) {
        $orderId = $data['id'];
        $newStatus = $data['status'];

        $stmt = $conn->prepare("UPDATE orders SET Status = ? WHERE ID = ?");
        $stmt->bind_param("si", $newStatus, $orderId);

        if($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Order updated to $newStatus"]);
        }
        else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
?>