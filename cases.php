<?php include("includes/db.php"); include("includes/header.php"); include("includes/sidebar.php");
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalCases = $conn->query("SELECT COUNT(*) as count FROM form_submissions")->fetch_assoc()['count'];
$totalPages = ceil($totalCases / $limit);

$result = $conn->query("SELECT * FROM form_submissions ORDER BY id DESC");
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Lawdit - Lawyer and Law Firm Template</title>
    <link rel="shortcut icon" href="img/favicon.png" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <link rel="stylesheet" href="css/plugins.css" />
    <link rel="stylesheet" href="css/style.css" />
<h3>Cases</h3>
<table class="table table-bordered">
  <thead>
    <tr><th>ID</th><th>Name</th><th>City</th><th>Service</th><th>Payment Mode</th><th>Date</th><th>Status</th><th>Action</th></tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['city'] ?></td>
      <td><?= $row['service'] ?></td>
      <td><?= $row['payment_mode'] ?></td>
      <td><?= $row['created_at'] ?></td>
      <td><?= $row['status'] ?></td>
      <td>
        <button class="btn btn-sm btn-info" onclick='openModal(<?= json_encode($row) ?>)'>View</button>
      </td>
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

<!-- Case Modal -->
<div class="modal fade" id="caseModal" tabindex="-1">
  <div class="modal-dialog modal-lg"><div class="modal-content p-4">
    <h5>Case Details</h5>
    <div id="caseDetails"></div>
    <div class="form-group mt-3">
      <label>Update Status</label>
      <select id="caseStatus" class="form-control">
        <option>Received</option>
        <option>Under Review</option>
        <option>In Progress</option>
        <option>Completed</option>
      </select>
      <button class="btn btn-primary mt-2" onclick="updateStatus()">Update</button>
    </div>
  </div></div>
</div>

<script>
let selectedCaseId = 0;
function openModal(data) {
  selectedCaseId = data.id;
  const details = `
    <p><strong>Name:</strong> ${data.name}</p>
    <p><strong>Mobile:</strong> ${data.mobile}</p>
    <p><strong>Address:</strong> ${data.address}</p>
    <p><strong>City:</strong> ${data.city}</p>
    <p><strong>Service:</strong> ${data.service}</p>
    <p><strong>Amount:</strong> ₹${data.amount}</p>
    <p><strong>Date:</strong> ${data.created_at}</p>
  `;
  document.getElementById("caseDetails").innerHTML = details;
  document.getElementById("caseStatus").value = data.status;
  new bootstrap.Modal(document.getElementById('caseModal')).show();
}

function updateStatus() {
  const status = document.getElementById("caseStatus").value;
  fetch("update_status.php", {
    method: "POST",
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${selectedCaseId}&status=${status}`
  }).then(res => res.text()).then(res => {
    if (res === "success") location.reload();
    else alert("Failed to update.");
  });
}
</script>
    <script src="js/bootstrap.min.js"></script>

<?php include("includes/footer.php"); ?>
