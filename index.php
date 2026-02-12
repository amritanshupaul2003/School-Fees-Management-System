<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// ---------- Safe Query Helper ----------
function safeQuery($conn, $sql)
{
  $result = $conn->query($sql);
  if (!$result) {
    error_log("SQL Error: " . $conn->error . " | Query: " . $sql);
    return false;
  }
  return $result;
}

// ---------- Fetch Stats ----------
$total_students = 0;
$active_students = 0;
$total_collected = 0;
$pending_fees = 0;

// Total students
if ($res = safeQuery($conn, "SELECT COUNT(*) AS c FROM students")) {
  $row = $res->fetch_assoc();
  $total_students = $row['c'];
}

// Active students
if ($res = safeQuery($conn, "SELECT COUNT(*) AS c FROM students WHERE status='Active'")) {
  $row = $res->fetch_assoc();
  $active_students = $row['c'];
}

// Fees collected
if ($res = safeQuery($conn, "SELECT IFNULL(SUM(amount),0) AS t FROM fee_payments")) {
  $row = $res->fetch_assoc();
  $total_collected = $row['t'];
}

// Pending fees (sum of structure - payments per student)
if (
  $res = safeQuery($conn, "
    SELECT s.student_id, 
           (SELECT IFNULL(SUM(amount),0) FROM fee_structure WHERE class=s.class) -
           (SELECT IFNULL(SUM(amount),0) FROM fee_payments WHERE student_id=s.student_id) AS pending
    FROM students s
")
) {
  while ($row = $res->fetch_assoc()) {
    if ($row['pending'] > 0) {
      $pending_fees += $row['pending'];
    }
  }
}

// Recent payments
$recent_payments = safeQuery($conn, "
    SELECT fp.payment_id, fp.student_id, s.name, fp.amount, fp.payment_date, fp.payment_mode
    FROM fee_payments fp
    JOIN students s ON fp.student_id = s.student_id
    ORDER BY fp.payment_date DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard - School Fees Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="reload" content="0; url=login.php">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Dashboard Content -->
  <div class="container mt-4">

    <!-- Stats -->
    <div class="row g-4 mb-4 text-center">
      <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3 bg-primary text-white">
          <h5>Total Students</h5>
          <h2><?= $total_students; ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3 bg-success text-white">
          <h5>Active Students</h5>
          <h2><?= $active_students; ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3 bg-warning text-dark">
          <h5>Fees Collected</h5>
          <h2>₹<?= number_format($total_collected, 2); ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3 bg-danger text-white">
          <h5>Pending Fees</h5>
          <h2>₹<?= number_format($pending_fees, 2); ?></h2>
        </div>
      </div>
    </div>

    <!-- Quick Navigation -->
    <div class="row g-4 mb-5">
      <div class="col-md-3">
        <div class="card shadow-sm text-center border-0 h-100">
          <div class="card-body">
            <i class="bi bi-people-fill display-4 text-primary"></i>
            <h5 class="mt-3">Students</h5>
            <a href="students.php" class="btn btn-outline-primary btn-sm">Manage</a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm text-center border-0 h-100">
          <div class="card-body">
            <i class="bi bi-cash-coin display-4 text-success"></i>
            <h5 class="mt-3">Fee Structure</h5>
            <a href="fees.php" class="btn btn-outline-success btn-sm">Manage</a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm text-center border-0 h-100">
          <div class="card-body">
            <i class="bi bi-receipt-cutoff display-4 text-warning"></i>
            <h5 class="mt-3">Payments</h5>
            <a href="payments.php" class="btn btn-outline-warning btn-sm">Manage</a>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm text-center border-0 h-100">
          <div class="card-body">
            <i class="bi bi-bar-chart-line-fill display-4 text-danger"></i>
            <h5 class="mt-3">Reports</h5>
            <a href="reports.php" class="btn btn-outline-danger btn-sm">View</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Payments -->
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Recent Payments</h5>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Student</th>
              <th>Amount</th>
              <th>Payment Date</th>
              <th>Mode</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($recent_payments && $recent_payments->num_rows > 0): ?>
              <?php while ($row = $recent_payments->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['payment_id']; ?></td>
                  <td><?= htmlspecialchars($row['name']); ?> (<?= $row['student_id']; ?>)</td>
                  <td>₹<?= number_format($row['amount'], 2); ?></td>
                  <td><?= $row['payment_date']; ?></td>
                  <td><?= $row['payment_mode']; ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No payments found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>