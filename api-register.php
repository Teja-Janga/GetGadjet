<?php 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    require 'database.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if($data) {
        $name = $data['name'];
        $email = $data['email'];
        $pass = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (Name, Email, Password_Hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $pass);

        if($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User registered!"]);
        }
        else {
            echo json_encode(["status" => "error", "message" => "Email already exists!"]);
        }
    }
?>