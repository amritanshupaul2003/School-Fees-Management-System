<?php
$conn = new mysqli("localhost", "root", "", "school_fees_db");

// Run query
$result = $conn->query("SELECT * FROM students");

if (!$result) {
    die("Database connection failed: " . $conn->error);
}
