<?php
$mobile = $_POST['mobile'];
$conn = new mysqli("localhost", "root", "", "law_firm");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$stmt = $conn->prepare("SELECT * FROM users WHERE mobile = ?");
$stmt->bind_param("s", $mobile);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // ✅ Start session and set user ID
    session_start();
    $_SESSION['user_id'] = $user['id'];

    echo "exists";
} else {
    echo "not_found";
}

$conn->close();
?>
