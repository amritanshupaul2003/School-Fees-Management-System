<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $parent_name = $_POST['parent_name'];
    $parent_contact = $_POST['parent_contact'];
    $parent_email = $_POST['parent_email'];
    $address = $_POST['address'];
    $admission_date = $_POST['admission_date'];
    $status = $_POST['status'];
    
    // Check if student ID already exists
    $check_stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Student ID already exists. Please use a different ID.";
    } else {
        // Insert new student
        $stmt = $conn->prepare("INSERT INTO students (student_id, name, class, section, parent_name, parent_contact, parent_email, address, admission_date, status) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $student_id, $name, $class, $section, $parent_name, $parent_contact, $parent_email, $address, $admission_date, $status);
        
        if ($stmt->execute()) {
            $success = "Student added successfully!";
            // Clear form fields
            $_POST = array();
        } else {
            $error = "Error adding student: " . $conn->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student - School Fees Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Add New Student</h4>
    </div>
    <div class="card-body">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>
      
      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="student_id" name="student_id" 
                 value="<?= isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
          <label for="name" class="form-label">Student Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" 
                 value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
        </div>
        
        <div class="col-md-4">
          <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="class" name="class" 
                 value="<?= isset($_POST['class']) ? htmlspecialchars($_POST['class']) : '' ?>" required>
        </div>
        
        <div class="col-md-4">
          <label for="section" class="form-label">Section</label>
          <input type="text" class="form-control" id="section" name="section" 
                 value="<?= isset($_POST['section']) ? htmlspecialchars($_POST['section']) : '' ?>">
        </div>
        
        <div class="col-md-4">
          <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
          <select class="form-select" id="status" name="status" required>
            <option value="Active" <?= (isset($_POST['status']) && $_POST['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= (isset($_POST['status']) && $_POST['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
        
        <div class="col-md-6">
          <label for="parent_name" class="form-label">Parent Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="parent_name" name="parent_name" 
                 value="<?= isset($_POST['parent_name']) ? htmlspecialchars($_POST['parent_name']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
          <label for="parent_contact" class="form-label">Parent Contact <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="parent_contact" name="parent_contact" 
                 value="<?= isset($_POST['parent_contact']) ? htmlspecialchars($_POST['parent_contact']) : '' ?>" required>
        </div>
        
        <div class="col-md-6">
          <label for="parent_email" class="form-label">Parent Email</label>
          <input type="email" class="form-control" id="parent_email" name="parent_email" 
                 value="<?= isset($_POST['parent_email']) ? htmlspecialchars($_POST['parent_email']) : '' ?>">
        </div>
        
        <div class="col-md-6">
          <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="admission_date" name="admission_date" 
                 value="<?= isset($_POST['admission_date']) ? $_POST['admission_date'] : date('Y-m-d') ?>" required>
        </div>
        
        <div class="col-12">
          <label for="address" class="form-label">Address</label>
          <textarea class="form-control" id="address" name="address" rows="3"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
        </div>
        
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Add Student</button>
          <a href="students.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>