<!--
In line.no: 17 why we use "time()_" for filename:
    Imagine I upload "iphone.jpg" today, and then next month you upload
    a different "iphone.jpg". Without time(), the new image would
    overwrite the old one! By adding the timestamp, the files stay
    unique (e.g., 17154321_iphone.jpg). 
-->

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    require 'database.php';

    if($_FILES['image']) {
        $targetDir = "../images/";

        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $title = $_POST['title'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $desc = $_POST['description'];

            $dbPath = "images/" . $fileName;

            $stmt = $conn->prepare("INSERT INTO products (Title, Price, Category, Image, Description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $title, $price, $category, $dbPath, $desc);


            if($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "New Gadjet added to the inventory!"]);
            }
            else {
                echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
            }
        }

        else {
            echo json_encode(["status" => "error", "message" => "Failed to move file to images folder."]);
        }
    }
?>