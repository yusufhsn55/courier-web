<?php
session_start();
require 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Input validation
    if (empty($username) || empty($password)) {
        echo '<script>alert("Please fill in all fields.");</script>';
        exit;
    }

    // Prepare SQL query
    $query = "SELECT id, role, password, status FROM users_tbl WHERE username = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if username exists
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $role, $password, $status);
            $stmt->fetch();

            // Verify password
            if ($password) {
                if ($status == 0) {
                    echo '<script>alert("User not updated by admin.");</script>';
                } elseif ($status == 1) {
                    // Set session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['role'] = $role;

                    // Function to handle session and redirection
                    getSessionAndRedirect($role);
                }
            } else {
                echo '<script>alert("Invalid password.");</script>';
            }
        } else {
            echo '<script>alert("No account found with that username.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Database query failed.");</script>';
    }
    $conn->close();
}

function getSessionAndRedirect($role) {
    if ($role) {
        // Redirect based on role
        switch ($role) {
            case 'Admin':
                header("Location: main/admin/");
                break;
            case 'Courier Manager':
                header("Location: main/courier_manager");
                break;
            case 'Dispatcher':
                header("Location: main/courier");
                break;
            case 'Customer':
                header("Location: main/customer/");
                break;
            case 'Warehouse Staff':
                header("Location: main/staff");
                break;
            default:
                echo "Invalid role.";
                break;
        }
        exit();
    } else {
        echo "Invalid role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KUSHITIC | Panel</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">KUSHITIC <span>COURIER MANAGEMENT SYSTEM</span> </div>
        <div class="nav-links" id="navLinks">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="registration/dispatcher_signup.php">Dispatcher(Courier)</a></li>
                <li><a href="registration/customer_signup.php">Customer</a></li>
               
            </ul>
        </div>
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
    </div>

    