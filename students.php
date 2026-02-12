<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Fetch students
$result = $conn->query("SELECT * FROM students ORDER BY class, section, name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Students - School Fees Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Students</h3>
    <a href="add_students.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add Student
    </a>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"></h3>
    <a href="student_edit.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Edit Student
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Parent</th>
            <th>Contact</th>
            <th>Status</th>
            <th width="150">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['student_id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= $row['class']; ?></td>
                <td><?= $row['section']; ?></td>
                <td><?= htmlspecialchars($row['parent_name']); ?></td>
                <td><?= $row['parent_contact']; ?></td>
                <td>
                  <span class="badge bg-<?= $row['status']=='Active'?'success':'secondary'; ?>">
                    <?= $row['status']; ?>
                  </span>
                </td>
                <td>
                  <a href="student_edit.php?id=<?= $row['student_id']; ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="student_delete.php?id=<?= $row['student_id']; ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this student?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center text-muted">No students found</td>
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
