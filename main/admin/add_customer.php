<?php
include('includes/header.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $occupation = 'Customer';
    
    
    $username = strtolower(substr($fullName, 0, 3)) . rand(100, 999);
    $rawPassword = bin2hex(random_bytes(4)); 
    $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);
    
    $nameParts = explode(" ", $fullName);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    
    $sql_check = "SELECT id FROM users_tbl WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>
                    alert('Username already exists. Please try again.');
                    window.location.href = 'add_users.php';
                  </script>";
        } else {
            
            $sql_users = "INSERT INTO users_tbl (role, username, password, created_at, Status) VALUES (?, ?, ?, NOW(), 1)";
            $stmt_users = $conn->prepare($sql_users);
            if ($stmt_users) {
                $stmt_users->bind_param("sss", $occupation, $username, $hashedPassword);

                if ($stmt_users->execute()) {
                    $userId = $stmt_users->insert_id;

                    
                    $sql_customers = "INSERT INTO customers (user_id, first_name, last_name, email, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
                    $stmt_customers = $conn->prepare($sql_customers);
                    if ($stmt_customers) {
                        $stmt_customers->bind_param("issss", $userId, $firstName, $lastName, $email, $phone);

                        if ($stmt_customers->execute()) {
                           
                            $mail = new PHPMailer(true);
                            try {
                               
                                $mail->isSMTP();
                                $mail->Host = 'smtp.gmail.com';
                                $mail->SMTPAuth = true;
                                $mail->Username = 'trippyaian@gmail.com';
                                $mail->Password = 'zskd yvtu asfe jemk';
                                $mail->SMTPSecure = 'ssl';
                                $mail->Port = 465;

                                
                                $mail->setFrom('trippyaian@gmail.com', 'KUSHITIC COURIER SYSTEMS USER REGISTRATION NOTICE');
                                $mail->addAddress($email, $fullName);

                                
                                $mail->isHTML(true);
                                $mail->Subject = 'Your Account Credentials';
                                $mail->Body = "Hello $firstName,<br>Your account has been created successfully.<br><br>Username: $username<br>Password: $rawPassword<br><br>Please change your password after your first login.";

                                $mail->send();
                                echo "<script>alert('Registration successful! Email sent.');</script>";
                            } catch (Exception $e) {
                                echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                            }
                            
                            echo "<script>
                                    alert('Registration successful!');
                                    window.location.href = 'all_customers.php';
                                  </script>";
                        } else {
                            echo "<script>
                                    alert('Error in customer registration.');
                                    window.location.href = 'all_customers.php';
                                  </script>";
                        }
                    } else {
                        echo "<script>
                                alert('Error preparing customer statement.');
                                window.location.href = 'all_customers.php';
                              </script>";
                    }
                } else {
                    echo "<script>
                            alert('Error in user registration.');
                            window.location.href = 'all_customers.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Error preparing user statement.');
                        window.location.href = 'all_customers.php';
                      </script>";
            }
        }

        $stmt_check->close();
        $stmt_users->close();
        if (isset($stmt_customers)) {
            $stmt_customers->close();
        }
    } else {
        echo "<script>
                alert('Error preparing check statement.');
                window.location.href = 'all_customerss.php';
              </script>";
    }

    $conn->close();
}
?>

<div class="container">
    <header>Customer Registration</header>

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
