<?php
include('includes/header.php');

if (!isset($conn)) {
    die("Database connection not established.");
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $national_id = $_POST['national_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_registration_number = $_POST['vehicle_registration_number'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_phone = $_POST['emergency_contact_phone'];
    $emergency_contact_relationship = $_POST['emergency_contact_relationship'];

    $nameParts = explode(" ", $fullName);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    $sql_users = "UPDATE users_tbl SET username = ?, password = ? WHERE id = ?";
    $stmt_users = $conn->prepare($sql_users);
    if ($stmt_users) {
        $stmt_users->bind_param("ssi", $username, $password, $userId);

        if ($stmt_users->execute()) {
            $sql_dispatchers = "UPDATE courier_dispatchers SET first_name = ?, last_name = ?, email = ?, phone_number = ?, address = ?, city = ?, postal_code = ?, country = ?, national_id = ?, vehicle_type = ?, vehicle_registration_number = ?, emergency_contact_name = ?, emergency_contact_phone = ?, emergency_contact_relationship = ? WHERE dispatcher_id = ?";
            $stmt_dispatchers = $conn->prepare($sql_dispatchers);
            if ($stmt_dispatchers) {
                $stmt_dispatchers->bind_param("ssssssssssssssi", $firstName, $lastName, $email, $phone, $address, $city, $postal_code, $country, $national_id, $vehicle_type, $vehicle_registration_number, $emergency_contact_name, $emergency_contact_phone, $emergency_contact_relationship, $userId);

                if ($stmt_dispatchers->execute()) {
                    echo "<script>
                            alert('Profile updated successfully!');
                            window.location.href = 'profile_management.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error in updating dispatcher profile.');
                            window.location.href = 'profile_management.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Error preparing dispatcher statement.');
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

    $stmt_users->close();
    if (isset($stmt_dispatchers)) {
        $stmt_dispatchers->close();
    }

    $conn->close();
}

$sql = "SELECT u.username, d.first_name, d.last_name, d.email, d.phone_number AS phone, d.address, d.city, d.postal_code, d.country, d.national_id, d.vehicle_type, d.vehicle_registration_number, d.emergency_contact_name, d.emergency_contact_phone, d.emergency_contact_relationship
        FROM users_tbl u
        JOIN courier_dispatchers d ON u.id = d.dispatcher_id
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
    <header>Edit Profile</header>

    <form action="" method="POST">
        <div class="form first">
            <div class="details personal">
                <span class="title">Personal Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" placeholder="Enter mobile number" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Physical Address</label>
                        <input type="text" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Enter your city" value="<?php echo htmlspecialchars($user['city']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Postal Code (Area Code)</label>
                        <input type="text" name="postal_code" placeholder="Enter your postal code" value="<?php echo htmlspecialchars($user['postal_code']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Country</label>
                        <input type="text" name="country" placeholder="Enter your country" value="<?php echo htmlspecialchars($user['country']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>National ID</label>
                        <input type="text" name="national_id" placeholder="Enter your national ID" value="<?php echo htmlspecialchars($user['national_id']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Vehicle Type</label>
                        <select name="vehicle_type" required>
                            <option disabled>Select Vehicle</option>
                            <option value="Cars" <?php if($user['vehicle_type'] == 'Cars') echo 'selected'; ?>>Car</option>
                            <option value="Vans" <?php if($user['vehicle_type'] == 'Vans') echo 'selected'; ?>>Van</option>
                            <option value="Truck" <?php if($user['vehicle_type'] == 'Truck') echo 'selected'; ?>>Truck</option>
                            <option value="Motorcycle" <?php if($user['vehicle_type'] == 'Motorcycle') echo 'selected'; ?>>Motorcycle</option>
                            <option value="Bus Service" <?php if($user['vehicle_type'] == 'Bus Service') echo 'selected'; ?>>Bus Service</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Vehicle Registration Number</label>
                        <input type="text" name="vehicle_registration_number" placeholder="Enter your vehicle registration number" value="<?php echo htmlspecialchars($user['vehicle_registration_number']); ?>" required>
                    </div>
                </div>
            </div>
            <div class="details ID">
                <span class="title">Identity Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="input-field">
                        <label>Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" placeholder="Enter emergency contact name" value="<?php echo htmlspecialchars($user['emergency_contact_name']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" placeholder="Enter emergency contact phone" value="<?php echo htmlspecialchars($user['emergency_contact_phone']); ?>" required>
                    </div>
                    <div class="input-field">
                        <label>Emergency Contact Relationship</label>
                        <input type="text" name="emergency_contact_relationship" placeholder="Enter emergency contact relationship" value="<?php echo htmlspecialchars($user['emergency_contact_relationship']); ?>" required>
                    </div>
                </div>
                <button type="submit" class="nextBtn">
                    <span class="btnText">Update</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>
