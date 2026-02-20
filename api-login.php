<?php 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    require 'database.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if($data) {
        $email = $data['email'];
        $pass = $data['password'];

        $stmt = $conn->prepare("SELECT ID, Name, Password_Hash, Is_Admin FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user && password_verify($pass, $user['Password_Hash'])) {
            // Don't send the password back to React!
            unset($user['Password_Hash']);
            echo json_encode(["status" => "success", "user" => $user]);
        }
        else {
            echo json_encode(["status" => "error", "message" => "Invalid Credentials!"]);
        }
    }
?>