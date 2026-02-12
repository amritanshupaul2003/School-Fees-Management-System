<?php
session_start();
include 'config.php';
if (!isset($_SESSION['username'])) { header("Location: login.html"); exit(); }

$result = $conn->query("SELECT * FROM fee_structure ORDER BY class, fee_type");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Fee Structure</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <h3>Fee Structure</h3>
  <a href="fee_add.php" class="btn btn-success mb-3">➕ Add Fee</a>
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Class</th><th>Fee Type</th><th>Amount</th><th>Year</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row=$result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['class']; ?></td>
            <td><?= htmlspecialchars($row['fee_type']); ?></td>
            <td>₹<?= number_format($row['amount'],2); ?></td>
            <td><?= $row['academic_year']; ?></td>
            <td>
              <a href="fee_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="fee_delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete fee?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">No fee structure found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
