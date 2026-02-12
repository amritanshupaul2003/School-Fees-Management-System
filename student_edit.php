<?php
session_start();
include 'config.php';

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) { 
    header("Location: login.html"); 
    exit(); 
}

// Validate student ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = $_GET['id'];

// Securely fetch student using prepared statement
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// If student not found, redirect
if (!$student) {
    header("Location: students.php");
    exit();
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("UPDATE students 
        SET name=?, class=?, section=?, parent_name=?, parent_contact=?, parent_email=?, address=?, admission_date=?, status=? 
        WHERE student_id=?");
    $stmt->bind_param(
        "ssssssssss",
        $_POST['name'],
        $_POST['class'],
        $_POST['section'],
        $_POST['parent_name'],
        $_POST['parent_contact'],
        $_POST['parent_email'],
        $_POST['address'],
        $_POST['admission_date'],
        $_POST['status'],
        $id
    );
    if ($stmt->execute()) { 
        header("Location: students.php"); 
        exit(); 
    } else { 
        $error = "Error updating student: " . $conn->error; 
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
<h3>Edit Student</h3>
<?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="POST" class="row g-3">
  <div class="col-md-8"><label>Name</label><input type="text" name="name" class="form-control" value=""></div>
  <div class="col-md-4"><label>Class</label><input type="text" name="class" class="form-control" value=""></div>
  <div class="col-md-4"><label>Section</label><input type="text" name="section" class="form-control" value=""></div>
  <div class="col-md-6"><label>Parent Name</label><input type="text" name="parent_name" class="form-control" value=""></div>
  <div class="col-md-6"><label>Parent Contact</label><input type="text" name="parent_contact" class="form-control" value=""></div>
  <div class="col-md-6"><label>Parent Email</label><input type="email" name="parent_email" class="form-control" value=""></div>
  <div class="col-md-12"><label>Address</label><textarea name="address" class="form-control"></textarea></div>
  <div class="col-md-4"><label>Admission Date</label><input type="date" name="admission_date" class="form-control" value=""></div>
  <div class="col-md-4"><label>Status</label>
    <select name="status" class="form-control">
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>
  </div>
  <div class="col-12"><button type="submit" class="btn btn-success">Update</button></div>
</form>
</div>
</body>
</html>
