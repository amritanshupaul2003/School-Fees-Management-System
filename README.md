# School Fees Management System

A comprehensive web-based application designed to streamline the management of student fees, payments, and records for educational institutions.

## 🚀 Features

*   **User Authentication:** Secure login system for administrators and staff.
*   **Student Management:**
    *   Add new student records.
    *   View, edit, and update student details.
    *   Delete inactive student records.
*   **Fee Structure Management:**
    *   Define and manage fee structures for different classes and academic years.
    *   Add and edit fee types and amounts.
*   **Payment Processing:**
    *   Record fee payments for students.
    *   Generate payment receipts (referenced by `receipt_no`).
    *   Track payment modes (Cash, Cheque, Online, etc.).
*   **Reports:** Generate and view reports on fee collections and outstanding payments.

## 🛠️ Technologies Used

*   **Frontend:** HTML5, CSS3 (Bootstrap 5), JavaScript
*   **Backend:** PHP
*   **Database:** MySQL

## ⚙️ Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/amritanshupaul2003/School-Fees-Management-System.git
    ```

2.  **Set up the Web Server**
    *   Ensure you have a local server environment like XAMPP, WAMP, or LAMP installed.
    *   Move the project folder to your server's root directory (e.g., `htdocs` for XAMPP).

3.  **Database Configuration**
    *   Open your database management tool (e.g., phpMyAdmin).
    *   Create a new database named `school_fees_db`.
    *   Import the `databse.sql` file located in the project root to create the necessary tables and default data.
    *   *Note: The `databse.sql` file contains a default admin user.*

4.  **Connect to Database**
    *   Open `config.php` and update the database credentials if necessary:
        ```php
        $conn = new mysqli("localhost", "your_username", "your_password", "school_fees_db");
        ```
    *   Default XAMPP credentials are usually user: `root`, password: `""` (empty).

## 🔑 Usage

1.  **Login**
    *   Navigate to the project URL (e.g., `http://localhost/School-Fees-Management-System/`).
    *   Log in using the default credentials:
        *   **Username:** `admin`
        *   **Password:** `admin123`

2.  **Dashboard**
    *   Use the navigation bar to access different modules: Students, Fee Structure, Payments, and Reports.

## 📂 Project Structure

*   `config.php`: Database connection settings.
*   `databse.sql`: SQL script for database schema and seed data.
*   `index.php`: Main landing/dashboard page.
*   `login.php` & `login.html`: User authentication handling.
*   `students.php`, `add_students.php`, `student_edit.php`: Student management modules.
*   `fees.php`, `fee_add.php`, `fee_edit.php`: Fee structure management modules.
*   `payments.php`, `payment_add.php`: Payment processing modules.
*   `reports.php`: Reporting module.
