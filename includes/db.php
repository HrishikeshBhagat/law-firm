<?php
$conn = new mysqli("localhost", "root", "", "law_firm");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);
session_start();
?>
