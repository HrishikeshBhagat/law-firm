<?php
$conn = new mysqli("localhost", "root", "", "law_firm");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

// To Prevent session_start() warning if session already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
