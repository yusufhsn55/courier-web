<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = $_POST['user_id'];
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

    // Check if the username already exists and is not the current user's username
    $sql_check = "SELECT id FROM users_tbl WHERE username = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $username, $userId);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Username already exists
        echo "<script>
                alert('Username already exists. Please choose a different username.');
                window.location.href = 'edit_user.php?user_id=$userId';
              </script>";
    } else {
        // Update data in users_tbl
        $sql_users = "UPDATE users_tbl SET role = ?, username = ?, password = ? WHERE id = ?";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("sssi", $occupation, $username, $password, $userId);

        if ($stmt_users->execute()) {
            // Update data in employees_tbl
            $sql_employees = "UPDATE employees_tbl SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE emply_id = ?";
            $stmt_employees = $conn->prepare($sql_employees);
            $stmt_employees->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);

            if ($stmt_employees->execute()) {
                echo "<script>
                        alert('Update successful!');
                        window.location.href = 'all_users.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error in employee update.');
                        window.location.href = 'edit_user.php?user_id=$userId';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error in user update.');
                    window.location.href = 'edit_user.php?user_id=$userId';
                  </script>";
        }
    }

    // Close the statements
    $stmt_check->close();
    $stmt_users->close();
    $stmt_employees->close();

    // Close the connection
    $conn->close();
} else {
    // Fetch the user data to populate the form
    $userId = $_GET['user_id'];
    $sql_user = "SELECT * FROM users_tbl LEFT JOIN employees_tbl ON users_tbl.id = employees_tbl.emply_id WHERE users_tbl.id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $userId);
    $stmt_user->execute();
    $result = $stmt_user->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>
                alert('User not found.');
                window.location.href = 'all_users.php';
              </script>";
    }

    // Close the statement
    $stmt_user->close();
}
?>

<div class="container">
    <header>Edit User</header>

    <form action="" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <div class="form first">
            <div class="details personal">
                <span class="title">Personal Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>" placeholder="Enter your name" required>
                    </div>
                    <div class="input-field">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo $user['dob']; ?>" placeholder="Enter birth date" required>
                    </div>
                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Enter your email" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Enter mobile number" required>
                    </div>
                    <div class="input-field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option disabled>Select gender</option>
                            <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Others" <?php if ($user['gender'] == 'Others') echo 'selected'; ?>>Others</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>User Role</label>
                        <select name="occupation" required>
                            <option disabled>Select user role</option>
                            <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                            <option value="Courier Manager" <?php if ($user['role'] == 'Courier Manager') echo 'selected'; ?>>Courier Manager</option>
                            <option value="Courier" <?php if ($user['role'] == 'Courier') echo 'selected'; ?>>Courier</option>
                            <option value="Customer Service Representative" <?php if ($user['role'] == 'Customer Service Representative') echo 'selected'; ?>>Customer Service Representative</option>
                            <option value="Customer" <?php if ($user['role'] == 'Customer') echo 'selected'; ?>>Customer</option>
                            <option value="Warehouse Staff" <?php if ($user['role'] == 'Warehouse Staff') echo 'selected'; ?>>Warehouse Staff</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="details ID">
                <span class="title">Identity Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo $user['username']; ?>" placeholder="Enter username" required>
                    </div>
                    <div class="input-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter new password" required>
                    </div>
                </div>
                <button type="submit" class="nextBtn">
                    <span class="btnText">Update</span>
                    <i class="uil uil-navigator"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<?php include('includes/footer.php'); ?>
