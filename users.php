<?php include("includes/db.php"); include("includes/header.php"); include("includes/sidebar.php");

$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalPages = ceil($totalUsers / $limit);
$result = $conn->query("SELECT * FROM users LIMIT $limit OFFSET $offset");


$result = $conn->query("SELECT * FROM users");
?>

<h3>Users</h3>
<table class="table table-bordered">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Address</th></tr></thead>
  <tbody>
    <?php while ($u = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= $u['name'] ?></td>
      <td><?= $u['email'] ?></td>
      <td><?= $u['mobile'] ?></td>
      <td><?= $u['address'] ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>

<?php include("includes/footer.php"); ?>
