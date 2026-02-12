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
    $class = $_POST['class'];
    $fee_type = $_POST['fee_type'];
    $amount = $_POST['amount'];
    $academic_year = $_POST['academic_year'];
    
    // Validate inputs
    if (empty($class) || empty($fee_type) || empty($amount) || empty($academic_year)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = "Amount must be a valid positive number.";
    } else {
        // Check if fee structure already exists for this class and fee type
        $check_stmt = $conn->prepare("SELECT id FROM fee_structure WHERE class = ? AND fee_type = ? AND academic_year = ?");
        $check_stmt->bind_param("sss", $class, $fee_type, $academic_year);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Fee structure already exists for this class, fee type, and academic year.";
        } else {
            // Insert new fee structure
            $stmt = $conn->prepare("INSERT INTO fee_structure (class, fee_type, amount, academic_year) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $class, $fee_type, $amount, $academic_year);
            
            if ($stmt->execute()) {
                $success = "Fee structure added successfully!";
                // Clear form fields
                $_POST = array();
            } else {
                $error = "Error adding fee structure: " . $conn->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Fee Structure - School Fees Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      border-radius: 10px 10px 0 0 !important;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
      border: none;
      padding: 10px 20px;
      font-weight: 600;
    }
    .btn-secondary {
      background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
      border: none;
      padding: 10px 20px;
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><i class="bi bi-currency-rupee"></i> Add New Fee Structure</h4>
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
              <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
              <select class="form-select" id="class" name="class" required>
                <option value="">Select Class</option>
                <option value="Nursery" <?= (isset($_POST['class']) && $_POST['class'] == 'Nursery') ? 'selected' : '' ?>>Nursery</option>
                <option value="KG" <?= (isset($_POST['class']) && $_POST['class'] == 'KG') ? 'selected' : '' ?>>KG</option>
                <option value="1" <?= (isset($_POST['class']) && $_POST['class'] == '1') ? 'selected' : '' ?>>Class 1</option>
                <option value="2" <?= (isset($_POST['class']) && $_POST['class'] == '2') ? 'selected' : '' ?>>Class 2</option>
                <option value="3" <?= (isset($_POST['class']) && $_POST['class'] == '3') ? 'selected' : '' ?>>Class 3</option>
                <option value="4" <?= (isset($_POST['class']) && $_POST['class'] == '4') ? 'selected' : '' ?>>Class 4</option>
                <option value="5" <?= (isset($_POST['class']) && $_POST['class'] == '5') ? 'selected' : '' ?>>Class 5</option>
                <option value="6" <?= (isset($_POST['class']) && $_POST['class'] == '6') ? 'selected' : '' ?>>Class 6</option>
                <option value="7" <?= (isset($_POST['class']) && $_POST['class'] == '7') ? 'selected' : '' ?>>Class 7</option>
                <option value="8" <?= (isset($_POST['class']) && $_POST['class'] == '8') ? 'selected' : '' ?>>Class 8</option>
                <option value="9" <?= (isset($_POST['class']) && $_POST['class'] == '9') ? 'selected' : '' ?>>Class 9</option>
                <option value="10" <?= (isset($_POST['class']) && $_POST['class'] == '10') ? 'selected' : '' ?>>Class 10</option>
                <option value="11" <?= (isset($_POST['class']) && $_POST['class'] == '11') ? 'selected' : '' ?>>Class 11</option>
                <option value="12" <?= (isset($_POST['class']) && $_POST['class'] == '12') ? 'selected' : '' ?>>Class 12</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
              <select class="form-select" id="fee_type" name="fee_type" required>
                <option value="">Select Fee Type</option>
                <option value="Tuition Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Tuition Fee') ? 'selected' : '' ?>>Tuition Fee</option>
                <option value="Admission Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Admission Fee') ? 'selected' : '' ?>>Admission Fee</option>
                <option value="Examination Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Examination Fee') ? 'selected' : '' ?>>Examination Fee</option>
                <option value="Library Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Library Fee') ? 'selected' : '' ?>>Library Fee</option>
                <option value="Sports Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Sports Fee') ? 'selected' : '' ?>>Sports Fee</option>
                <option value="Computer Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Computer Fee') ? 'selected' : '' ?>>Computer Fee</option>
                <option value="Transportation Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Transportation Fee') ? 'selected' : '' ?>>Transportation Fee</option>
                <option value="Lab Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Lab Fee') ? 'selected' : '' ?>>Lab Fee</option>
                <option value="Activity Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Activity Fee') ? 'selected' : '' ?>>Activity Fee</option>
                <option value="Development Fee" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Development Fee') ? 'selected' : '' ?>>Development Fee</option>
                <option value="Other" <?= (isset($_POST['fee_type']) && $_POST['fee_type'] == 'Other') ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" class="form-control" id="amount" name="amount" 
                       value="<?= isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : '' ?>" 
                       step="0.01" min="0" required>
              </div>
            </div>
            
            <div class="col-md-6">
              <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
              <select class="form-select" id="academic_year" name="academic_year" required>
                <option value="">Select Academic Year</option>
                <?php
                $current_year = date('Y');
                for ($i = -2; $i <= 2; $i++) {
                    $year = $current_year + $i;
                    $next_year = $year + 1;
                    $academic_year_value = $year . '-' . substr($next_year, 2);
                    $selected = (isset($_POST['academic_year']) && $_POST['academic_year'] == $academic_year_value) ? 'selected' : '';
                    echo "<option value=\"$academic_year_value\" $selected>$year-$next_year</option>";
                }
                ?>
              </select>
            </div>
            
            <div class="col-12 mt-4">
              <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Fee Structure</button>
              <a href="fees.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Fees</a>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Help Section -->
      <div class="card shadow-sm mt-4">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="bi bi-info-circle"></i> About Fee Structure</h5>
        </div>
        <div class="card-body">
          <p>The fee structure defines the different types of fees and their amounts for each class and academic year.</p>
          <ul>
            <li><strong>Class:</strong> Select the class for which you're defining the fee</li>
            <li><strong>Fee Type:</strong> Choose the category of fee (e.g., Tuition, Admission, etc.)</li>
            <li><strong>Amount:</strong> Enter the fee amount in Indian Rupees (₹)</li>
            <li><strong>Academic Year:</strong> Select the academic year this fee applies to</li>
          </ul>
          <p class="mb-0">Once added, this fee structure will be used when recording payments from students.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>