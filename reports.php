<?php
session_start();
include 'config.php';
if (!isset($_SESSION['username'])) { header("Location: login.html"); exit(); }

$total_students = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$total_payments = $conn->query("SELECT IFNULL(SUM(amount),0) AS t FROM fee_payments")->fetch_assoc()['t'];

$payments_by_class = $conn->query("
  SELECT s.class, SUM(fp.amount) as total 
  FROM fee_payments fp 
  JOIN students s ON fp.student_id = s.student_id 
  GROUP BY s.class
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <h3>Reports</h3>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card p-3 bg-info text-white">
        <h5>Total Students</h5>
        <h2><?= $total_students; ?></h2>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 bg-success text-white">
        <h5>Total Fees Collected</h5>
        <h2>₹<?= number_format($total_payments,2); ?></h2>
      </div>
    </div>
  </div>
  <div class="card p-3">
    <h5>Fees Collected by Class</h5>
    <canvas id="classChart"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('classChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php $labels=[]; $data=[]; while($row=$payments_by_class->fetch_assoc()){ $labels[]=$row['class']; $data[]=$row['total']; } echo "'".implode("','",$labels)."'"; ?>],
    datasets: [{
      label: 'Fees Collected',
      data: [<?= implode(",",$data); ?>],
      backgroundColor: 'rgba(54, 162, 235, 0.7)'
    }]
  },
  options: { responsive: true }
});
</script>
</body>
</html>
