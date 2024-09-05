<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Split the full name into first name and last name
    $nameParts = explode(" ", $fullName);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Update data in users_tbl
    $sql_users = "UPDATE users_tbl SET username = ?, password = ? WHERE id = ?";
    $stmt_users = $conn->prepare($sql_users);
    if ($stmt_users) {
        $stmt_users->bind_param("ssi", $username, $password, $userId);

        if ($stmt_users->execute()) {
            // Update data in customers
            $sql_customers = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?";
            $stmt_customers = $conn->prepare($sql_customers);
            if ($stmt_customers) {
                $stmt_customers->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);

                if ($stmt_customers->execute()) {
                    echo "<script>
                            alert('Profile updated successfully!');
                            window.location.href = 'profile_management.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error in updating customer profile.');
                            window.location.href = 'profile_management.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Error preparing customer statement.');
                        window.location.href = 'profile_management.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error in updating user profile.');
                    window.location.href = 'profile_management.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error preparing user statement.');
                window.location.href = 'profile_management.php';
              </script>";
    }

    // Close the statements
    $stmt_users->close();
    if (isset($stmt_customers)) {
        $stmt_customers->close();
    }

    // Close the connection
    $conn->close();
}

// Fetch user and customer details
$sql = "SELECT u.username, c.first_name, c.last_name, c.email, c.phone
        FROM users_tbl u
        JOIN customers c ON u.id = c.user_id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Error preparing SQL statement.");
}
?>

<div class="container">
    <div class="card profile-card">
        <h2>Profile Update</h2>
        <form action="" method="POST">
            <div class="row">
                <div class="col">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>" required>
                </div>
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $user['email']; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="phone">Mobile Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter mobile number" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div class="col">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo $user['username']; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn-submit">Update Profile</button>
            </div>
        </form>
    </div>

    <div class="card profile-details">
        <h2>Profile Details</h2>
        <ul>
            <li><strong>Username:</strong> <?php echo $user['username']; ?></li>
            <li><strong>First Name:</strong> <?php echo $user['first_name']; ?></li>
            <li><strong>Last Name:</strong> <?php echo $user['last_name']; ?></li>
            <li><strong>Email:</strong> <?php echo $user['email']; ?></li>
            <li><strong>Phone:</strong> <?php echo $user['phone']; ?></li>
        </ul>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .col {
        flex: 1;
        margin-right: 10px;
    }

    .col:last-child {
        margin-right: 0;
    }

    .btn-submit {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .profile-details ul {
        list-style: none;
        padding: 0;
    }

    .profile-details li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .profile-details li:last-child {
        border-bottom: none;
    }

    .profile-details strong {
        color: #333;
    }
</style>
