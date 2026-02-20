<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    require 'database.php';

    ini_set('display_errors', 0); // Disable HTML error display for clean JSON

    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $customerName = $data['customerName'];
        $address = $data['address'];
        $phone = $data['phone'];
        $cart = $data['cart'];
        $userId = isset($data['userId']) ? $data['userId'] : 1;

        // 1. Insert into 'orders' table
        $stmt = $conn->prepare("INSERT INTO orders (Customer_Name, Address, Phone, User_ID, Status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("sssi", $customerName, $address, $phone, $userId);

        if($stmt->execute()) {
            $orderId = $conn->insert_id; // Get the ID of the order we just created

            // 2. Insert into 'order_items' table
            $stmtItem = $conn->prepare("INSERT INTO order_items (Order_id, Product_id, Quantity, Price) VALUES (?, ?, ?, ?)");

            foreach ($cart as $item) {
                $pId = $item['ID'];
                $pQty = $item['qty'];
                $pPrice = isset($item['Price']) ? $item['Price'] : 0;

                $stmtItem->bind_param("iiid", $orderId, $pId, $pQty, $pPrice);
                // $stmtItem->execute();
                if(!$stmtItem->execute()) {
                    error_log("Item Insert Failed: " . $stmtItem->error);
                }
            }
            echo json_encode(["status" => "success", "message" => "Order #$orderId placed successfully!", "orderId" => $orderId]);
        } 
        else {
            echo json_encode(["status" => "error", "message" => "Order insertion failed: ". $stmt->error]);
        }
    }
?>