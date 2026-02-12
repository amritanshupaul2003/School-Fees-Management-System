<?php
session_start();
include 'config.php'; // Your database connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Fetch students for dropdown
$students = $conn->query("SELECT student_id, name FROM students ORDER BY name ASC");
if (!$students) {
    die("Error fetching students: " . $conn->error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $receipt_no = $_POST['receipt_no'];
    $remarks = $_POST['remarks'];

    // Basic validation
    if (empty($student_id) || empty($amount) || empty($payment_date) || empty($payment_mode) || empty($receipt_no)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO fee_payments (student_id, amount, payment_date, payment_mode, receipt_no, remarks) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssss", $student_id, $amount, $payment_date, $payment_mode, $receipt_no, $remarks);

        if ($stmt->execute()) {
            $success = "Payment added successfully!";
        } else {
            $error = "Error adding payment: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f0f4f8;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 500px;
        margin: 50px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333333;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555555;
    }
    input, select, textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 18px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: border 0.2s ease;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #4f46e5;
        outline: none;
    }
    input[type="submit"] {
        background-color: #4f46e5;
        color: #fff;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #3730a3;
    }
    .success {
        background-color: #dafbe1;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .error {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Add Payment</h2>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>

    <form method="POST" action="">
        <label for="student_id">Student</label>
        <select name="student_id" id="student_id" required>
            <option value="">Select Student</option>
            <?php while($row = $students->fetch_assoc()): ?>
                <option value="<?= $row['student_id'] ?>"><?= $row['name'] ?> (<?= $row['student_id'] ?>)</option>
            <?php endwhile; ?>
        </select>

        <label for="amount">Amount</label>
        <input type="number" step="0.01" name="amount" id="amount" required>

        <label for="payment_date">Payment Date</label>
        <input type="date" name="payment_date" id="payment_date" required>

        <label for="payment_mode">Payment Mode</label>
        <select name="payment_mode" id="payment_mode" required>
            <option value="">Select Mode</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Online">Online</option>
            <option value="Cheque">Cheque</option>
        </select>

        <label for="receipt_no">Receipt Number</label>
        <input type="text" name="receipt_no" id="receipt_no" required>

        <label for="remarks">Remarks</label>
        <textarea name="remarks" id="remarks" rows="3"></textarea>

        <input type="submit" value="Add Payment">
    </form>
</div>

</body>
</html>
