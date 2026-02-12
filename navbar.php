<?php
if (!isset($_SESSION)) { session_start(); }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">School Fees Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="students.php">Students</a></li>
        <li class="nav-item"><a class="nav-link" href="fees.php">Fee Structure</a></li>
        <li class="nav-item"><a class="nav-link" href="payments.php">Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
        <li class="nav-item">
          <span class="nav-link text-white">👋 <?= htmlspecialchars($_SESSION['username']); ?></span>
        </li>
        <li class="nav-item">
          <a class="btn btn-sm btn-danger ms-2" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
