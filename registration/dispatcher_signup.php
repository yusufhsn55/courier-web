<?php
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $occupation = "Dispatcher";
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $national_id = mysqli_real_escape_string($conn, $_POST['national_id']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $vehicle_registration_number = mysqli_real_escape_string($conn, $_POST['vehicle_registration_number']);
    $emergency_contact_name = mysqli_real_escape_string($conn, $_POST['emergency_contact_name']);
    $emergency_contact_phone = mysqli_real_escape_string($conn, $_POST['emergency_contact_phone']);
    $emergency_contact_relationship = mysqli_real_escape_string($conn, $_POST['emergency_contact_relationship']);


    $nameParts = explode(" ", $fullName);
    $firstName = mysqli_real_escape_string($conn, $nameParts[0]);
    $lastName = isset($nameParts[1]) ? mysqli_real_escape_string($conn, $nameParts[1]) : '';


    $sql_check = "SELECT id FROM users_tbl WHERE username = '$username'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>
                alert('Username already exists. Please choose a different username.');
                window.location.href = '../';
              </script>";
    } else {
       
        $sql_users = "INSERT INTO users_tbl (role, username, password, created_at, status) 
                      VALUES ('$occupation', '$username', '$password', NOW(), '1')";

        if (mysqli_query($conn, $sql_users)) {
            $userId = mysqli_insert_id($conn);

            $sql_dispatchers = "INSERT INTO courier_dispatchers (
                dispatcher_id, first_name, last_name, email, phone_number, address, city, postal_code, country, 
                national_id, vehicle_type, vehicle_registration_number, date_joined, status, profile_picture, 
                emergency_contact_name, emergency_contact_phone, emergency_contact_relationship
            ) VALUES (
                '$userId', '$firstName', '$lastName', '$email', '$phone', '$address', '$city', '$postal_code', 
                '$country', '$national_id', '$vehicle_type', '$vehicle_registration_number', CURDATE(), 'Inactive', 
                NULL, '$emergency_contact_name', '$emergency_contact_phone', '$emergency_contact_relationship'
            )";

            if (mysqli_query($conn, $sql_dispatchers)) {
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = '../';
                      </script>";
            } else {
                echo "<script>
                        alert('Error in dispatcher registration.');
                        window.location.href = '../';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error in user registration.');
                    window.location.href = '../';
                  </script>";
        }
    }

    mysqli_close($conn);
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
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" placeholder="Enter mobile number" required>
                    </div>
                    
                    
                    <div class="input-field">
                        <label>Physical Address</label>
                        <input type="text" name="address" placeholder="Enter your address" required>
                    </div>
                    <div class="input-field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Enter your city" required>
                    </div>
                   
                    <div class="input-field">
                        <label>Postal Code(Areaa Code)</label>
                        <input type="text" name="postal_code" placeholder="Enter your postal code" required>
                    </div>
                    <div class="input-field">
                        <label>Country</label>
                        <input type="text" name="country" placeholder="Enter your country" required>
                    </div>
                    <div class="input-field">
                        <label>National ID</label>
                        <input type="text" name="national_id" placeholder="Enter your national ID" required>
                    </div> 
                    <div class="input-field">
                        <label>Vehicle Type</label>
                        <select name="vehicle_type" placeholder="Enter your vehicle type" required>
                            <option disabled selected>Select Vehicle</option>
                            <option value="Cars">Car</option>
                            <option value="Vans">Van</option>
                            <option value="Truck">Truck</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Bus Service">Bus Service</option>
                        </select>
                    </div>
                   
                    <div class="input-field">
                        <label>Vehicle Registration Number</label>
                        <input type="text" name="vehicle_registration_number" placeholder="Enter your vehicle registration number" required>
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
                    <div class="input-field">
                        <label>Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" placeholder="Enter emergency contact name" required>
                    </div>
                    <div class="input-field">
                        <label>Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" placeholder="Enter emergency contact phone" required>
                    </div>
                    <div class="input-field">
                        <label>Emergency Contact Relationship</label>
                        <input type="text" name="emergency_contact_relationship" placeholder="Enter emergency contact relationship" required>
                    </div>
                </div>
                <button type="submit" class="nextBtn">
                    <span class="btnText">Submit</span>
                </button>
              
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>
