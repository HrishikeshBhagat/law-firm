<?php
session_start();
header("Content-Type: text/plain"); // ✅ Avoid HTML headers

include("includes/db.php");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo "invalid_data";
    exit;
}

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$address = $data['address'] ?? '';
$mobile = $data['mobile'] ?? '';
$city = $data['city'] ?? '';
$service = $data['service'] ?? '';
$amount = $data['amount'] ?? 0;
$payment_mode = $data['payment_mode'] ?? 'Offline';

if (empty($name) || empty($mobile) || empty($address) || empty($service)) {
    echo "missing_fields";
    exit;
}

// Check or create user
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    // Try to find by mobile
    $stmt = $conn->prepare("SELECT id FROM users WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows) {
        $user_id = $result->fetch_assoc()['id'];
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, address, mobile) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $address, $mobile);
        $stmt->execute();
        $user_id = $stmt->insert_id;

        // Auto-login
        $_SESSION['user_id'] = $user_id;
    }
} else {
    // Existing user: update their info
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, address=?, mobile=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $address, $mobile, $user_id);
    $stmt->execute();
}

// Insert into form_submissions
$stmt = $conn->prepare("INSERT INTO form_submissions (user_id, name, email, mobile, address, city, service, amount, status, payment_mode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Received', ?)");
$stmt->bind_param("issssssds", $user_id, $name, $email, $mobile, $address, $city, $service, $amount, $payment_mode);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "db_error";
}
?>
