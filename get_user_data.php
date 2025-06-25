<?php
include("includes/db.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
echo json_encode($result->fetch_assoc());
