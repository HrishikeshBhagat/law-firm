<?php include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows) {
    $_SESSION['admin'] = $result->fetch_assoc();
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid credentials!";
  }
}
?>

<form method="post">
  <h2>Admin Login</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <input type="text" name="username" placeholder="Username" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit">Login</button>
</form>
