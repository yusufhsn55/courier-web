<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = $_POST['full_name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $occupation = $_POST['occupation'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Split the full name into first name and last name
    $nameParts = explode(" ", $fullName);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Check if the username already exists
    $sql_check = "SELECT id FROM users_tbl WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Username already exists
        echo "<script>
                alert('Username already exists. Please choose a different username.');
                window.location.href = 'add_users.php';
              </script>";
    } else {
        // Insert data into users_tbl
        $sql_users = "INSERT INTO users_tbl (role, username, password, created_at, Status) VALUES (?, ?, ?, NOW(), 1)";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("sss", $occupation, $username, $password);

        if ($stmt_users->execute()) {
            // Get the last inserted user ID
            $userId = $stmt_users->insert_id;

            // Insert data into employees_tbl
            $sql_employees = "INSERT INTO employees_tbl (emply_id, first_name, last_name, email, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt_employees = $conn->prepare($sql_employees);
            $stmt_employees->bind_param("issss", $userId, $firstName, $lastName, $email, $phone);

            if ($stmt_employees->execute()) {
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'all_users.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error in employee registration.');
                        window.location.href = 'add_users.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error in user registration.');
                    window.location.href = 'add_users.php';
                  </script>";
        }
    }

    // Close the statements
    $stmt_check->close();
    $stmt_users->close();
    $stmt_employees->close();

    // Close the connection
    $conn->close();
}
?>

<div class="container">
    <header>Account Registration</header>

    <form action="" method="POST">
        <div class="form first">
            <div class="details personal">
                <span class="title">Personal Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your name" required>
                    </div>
                    <div class="input-field">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" placeholder="Enter birth date" required>
                    </div>
                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" placeholder="Enter mobile number" required>
                    </div>
                    <div class="input-field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>User Role</label>
                        <select name="occupation" placeholder="Enter occupation" required>
                            <option disabled selected>Select user role</option>
                            <option value="Admin">Admin</option>
                            <option value="Courier Manager">Courier Manager</option>
                            <option value="Customer Service Representative">Customer Service Representative</option>
                            <option value="Customer">Customer</option>
                            <option value="Warehouse Staff">Warehouse Staff</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="details ID">
                <span class="title">Identity Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="input-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>
                </div>
                <button type="submit" class="nextBtn">
                    <span class="btnText">Register</span>
                    <i class="uil uil-navigator"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<?php include('includes/footer.php'); ?>
