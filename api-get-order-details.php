
<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    require 'database.php';

    if (isset($_GET['order_id'])) {
        $orderId = $_GET['order_id'];

        $sql = "SELECT oi.*, p.Title, p.Image
                FROM order_items oi
                JOIN products p ON oi.Product_id = p.ID
                WHERE oi.Order_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode($items);
    }
?>