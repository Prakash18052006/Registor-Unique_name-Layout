<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username exists and verify password
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo json_encode(["message" => "Login successful!"]);
            http_response_code(200);
        } else {
            echo json_encode(["error" => "Incorrect password."]);
            http_response_code(401);
        }
    } else {
        echo json_encode(["error" => "Username not found."]);
        http_response_code(404);
    }

    $stmt->close();
}

$conn->close();
?>
