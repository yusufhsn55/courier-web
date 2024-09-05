<?php
include('includes/header.php');

if (isset($_POST['customer_id'])) {
    $customer_id = intval($_POST['customer_id']);

    $sql = "SELECT c.*, u.username, u.role FROM customers c JOIN users_tbl u ON c.user_id = u.id WHERE c.id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();
        } else {
            echo "<script>
                    alert('Customer not found.');
                    window.location.href = 'all_customers.php';
                  </script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing statement.');
                window.location.href = 'all_customers.php';
              </script>";
        exit;
    }
} else {
    echo "<script>
            alert('No customer ID provided.');
            window.location.href = 'all_customers.php';
          </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_id'])) {
    // Retrieve form data
    $fullName = $_POST['full_name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $occupation = $_POST['occupation'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $customer['password'];

    // Split the full name into first name and last name
    $nameParts = explode(" ", $fullName);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Update users_tbl
    $sql_users = "UPDATE users_tbl SET role = ?, username = ?, password = ? WHERE id = ?";
    $stmt_users = $conn->prepare($sql_users);
    if ($stmt_users) {
        $stmt_users->bind_param("sssi", $occupation, $username, $password, $customer['user_id']);

        if ($stmt_users->execute()) {
            // Update customers
            $sql_customers = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, dob = ?, gender = ?, updated_at = NOW() WHERE id = ?";
            $stmt_customers = $conn->prepare($sql_customers);
            if ($stmt_customers) {
                $stmt_customers->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $dob, $gender, $customer_id);

                if ($stmt_customers->execute()) {
                    echo "<script>
                            alert('Customer updated successfully!');
                            window.location.href = 'all_customers.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error in updating customer.');
                            window.location.href = 'edit_customer.php?customer_id=' + encodeURIComponent('$customer_id');
                          </script>";
                }
                $stmt_customers->close();
            } else {
                echo "<script>
                        alert('Error preparing customer statement.');
                        window.location.href = 'edit_customer.php?customer_id=' + encodeURIComponent('$customer_id');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error in updating user.');
                    window.location.href = 'edit_customer.php?customer_id=' + encodeURIComponent('$customer_id');
                  </script>";
        }
        $stmt_users->close();
    } else {
        echo "<script>
                alert('Error preparing user statement.');
                window.location.href = 'edit_customer.php?customer_id=' + encodeURIComponent('$customer_id');
              </script>";
    }
}
?>

<div class="container">
    <header>Edit Customer</header>

    <form action="" method="POST">
        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
        <div class="form first">
            <div class="details personal">
                <span class="title">Personal Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>" placeholder="Enter your name" required>
                    </div>
                    <div class="input-field">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($customer['dob']); ?>" placeholder="Enter birth date" required>
                    </div>
                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" placeholder="Enter your email" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" placeholder="Enter mobile number" required>
                    </div>
                    <div class="input-field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option disabled>Select gender</option>
                            <option value="Male" <?php if ($customer['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($customer['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Others" <?php if ($customer['gender'] == 'Others') echo 'selected'; ?>>Others</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>User Role</label>
                        <select name="occupation" required>
                            <option disabled>Select user role</option>
                            <option value="Admin" <?php if ($customer['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                            <option value="Courier Manager" <?php if ($customer['role'] == 'Courier Manager') echo 'selected'; ?>>Courier Manager</option>
                            <option value="Customer Service Representative" <?php if ($customer['role'] == 'Customer Service Representative') echo 'selected'; ?>>Customer Service Representative</option>
                            <option value="Customer" <?php if ($customer['role'] == 'Customer') echo 'selected'; ?>>Customer</option>
                            <option value="Warehouse Staff" <?php if ($customer['role'] == 'Warehouse Staff') echo 'selected'; ?>>Warehouse Staff</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="details ID">
                <span class="title">Identity Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>" placeholder="Enter username" required>
                    </div>
                    <div class="input-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter new password (leave blank to keep current password)">
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
