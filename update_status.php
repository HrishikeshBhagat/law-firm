<?php
include("includes/db.php");

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE form_submissions SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
echo $stmt->execute() ? "success" : "error";
