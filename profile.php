<?php include("includes/db.php");
include("includes/header.php");
include("includes/sidebar.php");

$admin = $_SESSION['admin'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("UPDATE admins SET name=?, username=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $username, $admin['id']);
    if ($stmt->execute()) {
        $_SESSION['admin']['name'] = $name;
        $_SESSION['admin']['username'] = $username;
        echo "<p class='text-success'>Profile updated!</p>";
    } else {
        echo "<p class='text-danger'>Failed to update!</p>";
    }
}

// Change Password
if (isset($_POST['change_password'])) {
    $current = md5($_POST['current']);
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    if ($new !== $confirm) {
        echo "<p class='text-danger'>New passwords do not match.</p>";
    } else {
        $adminId = $_SESSION['admin']['id'];
        $check = $conn->query("SELECT * FROM admins WHERE id = $adminId AND password = '$current'");
        if ($check->num_rows > 0) {
            $hashedNew = md5($new);
            $conn->query("UPDATE admins SET password = '$hashedNew' WHERE id = $adminId");
            echo "<p class='text-success'>Password updated successfully!</p>";
        } else {
            echo "<p class='text-danger'>Current password is incorrect.</p>";
        }
    }
}
?>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
<link rel="stylesheet" href="css/plugins.css" />
<link rel="stylesheet" href="css/style.css" />
<h3>Admin Profile</h3>
<form method="POST">
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= $admin['name'] ?>" required>
    </div>
    <div class="form-group mt-2">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>" required>
    </div>
    <button class="btn btn-success mt-3">Update Profile</button>
</form>

<button type="button" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#changePassModal">
    Change Password
</button>

<!-- Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <h5>Change Password</h5>
            <form method="POST" action="profile.php">
                <input type="hidden" name="change_password" value="1">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current" class="form-control" required>
                </div>
                <div class="form-group mt-2">
                    <label>New Password</label>
                    <input type="password" name="new" class="form-control" required>
                </div>
                <div class="form-group mt-2">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <button class="btn btn-success mt-3">Update Password</button>
            </form>
        </div>
    </div>
</div>
<script src="js/bootstrap.min.js"></script>
<?php include("includes/footer.php"); ?>