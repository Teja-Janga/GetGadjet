
<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    require 'database.php';

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    try {
        // $sql = "SELECT * FROM orders ORDER BY ID DESC";
        // $sql = "SELECT 
        //             o.*, SUM(oi.Quantity * oi.Price) AS Total_Amount 
        //         FROM orders o
        //         LEFT JOIN order_items oi ON o.ID = oi.Order_id
        //         GROUP BY o.ID
        //         ORDER BY o.ID DESC";

        $sql = "SELECT 
                    o.*, 
                    COALESCE(SUM(oi.Quantity * oi.Price), 0) AS Total_Amount 
                FROM orders o
                LEFT JOIN order_items oi ON o.ID = oi.Order_id
                GROUP BY o.ID
                ORDER BY o.ID DESC";

        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception($conn->error);
        }

        $orders = [];
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode($orders);
    }
    catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>