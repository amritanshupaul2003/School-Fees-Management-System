<?php
session_start();
include 'config.php';
if (!isset($_SESSION['username'])) { header("Location: login.html"); exit(); }

$result = $conn->query("
  SELECT fp.*, s.name 
  FROM fee_payments fp
  JOIN students s ON fp.student_id = s.student_id
  ORDER BY fp.payment_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <h3>Payments</h3>
  <a href="payment_add.php" class="btn btn-warning mb-3">➕ Add Payment</a>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Student</th><th>Amount</th><th>Date</th><th>Mode</th><th>Receipt</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row=$result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['payment_id']; ?></td>
              <td><?= htmlspecialchars($row['name']); ?> (<?= $row['student_id']; ?>)</td>
              <td>₹<?= number_format($row['amount'],2); ?></td>
              <td><?= $row['payment_date']; ?></td>
              <td><?= $row['payment_mode']; ?></td>
              <td><?= $row['receipt_no']; ?></td>
              <td>
                <a href="payment_edit.php?id=<?= $row['payment_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="payment_delete.php?id=<?= $row['payment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete payment?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center">No payments found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
