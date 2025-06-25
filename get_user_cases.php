<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT id, service, status FROM form_submissions WHERE user_id = $user_id ORDER BY id DESC");
$cases = [];

while ($row = $result->fetch_assoc()) {
  $cases[] = $row;
}

echo json_encode($cases);
