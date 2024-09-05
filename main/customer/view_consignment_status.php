<?php
include('includes/header.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$trackId = $_GET['tracking_number'] ?? '';

if (!empty($trackId)) {
    $tracking_number = mysqli_real_escape_string($conn, $trackId);

    $sql = "SELECT * FROM consignments WHERE tracking_number = '$tracking_number'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $consignment = mysqli_fetch_assoc($result);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['request_dispatcher'])) {
                $sql_dispatcher = "SELECT dispatcher_id, first_name, last_name, email FROM courier_dispatchers WHERE status = 'Active' ORDER BY RAND() LIMIT 1";
                $dispatcher_result = mysqli_query($conn, $sql_dispatcher);

                if ($dispatcher_result && mysqli_num_rows($dispatcher_result) > 0) {
                    $dispatcher = mysqli_fetch_assoc($dispatcher_result);
                    $dispatcher_id = $dispatcher['dispatcher_id'];
                    $dispatcher_email = $dispatcher['email'];
                    $dispatcher_name = $dispatcher['first_name'] . ' ' . $dispatcher['last_name'];

                    $sql_update_dispatcher = "UPDATE consignments SET dispatcher_id = '$dispatcher_id' WHERE tracking_number = '$tracking_number'";
                    if (mysqli_query($conn, $sql_update_dispatcher)) {
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'trippyaian@gmail.com';
                            $mail->Password = 'zskd yvtu asfe jemk'; 
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                            $mail->Port = 465;

                            $mail->setFrom('trippyaian@gmail.com', 'KUSHITIC COURIER SYSTEMS PICKUP REQUEST ');
                            $mail->addAddress($dispatcher_email, $dispatcher_name);

                            $mail->isHTML(true);
                            $mail->Subject = 'Package Pickup Request';
                            $mail->Body = "Dear $dispatcher_name,<br><br>
                                You have been assigned to pick up the following package:<br><br>
                                Tracking Number: $tracking_number<br>
                                Pickup Address: " . htmlspecialchars($consignment['pickup_address']) . "<br>
                                Delivery Address: " . htmlspecialchars($consignment['delivery_address']) . "<br><br>
                                Please confirm the pickup and update the system accordingly.<br><br>
                                Best regards,<br>Your Courier Service Name";

                            $mail->send();

                            echo "<script>
                                    alert('Dispatcher request sent successfully! The selected dispatcher is $dispatcher_name.');
                                    window.location.href = 'shipping_status.php';
                                  </script>";
                        } catch (Exception $e) {
                            echo "<script>
                                    alert('Dispatcher assigned but email could not be sent. Mailer Error: {$mail->ErrorInfo}');
                                    window.location.href = 'view_consignment_status.php?tracking_number=' + encodeURIComponent('$tracking_number');
                                  </script>";
                        }
                    } else {
                        echo "<script>
                                alert('Error assigning dispatcher.');
                                window.location.href = 'view_consignment_status.php?tracking_number=' + encodeURIComponent('$tracking_number');
                              </script>";
                    }
                } else {
                    echo "<script>
                            alert('No active dispatchers available.');
                            window.location.href = 'view_consignment_status.php?tracking_number=' + encodeURIComponent('$tracking_number');
                          </script>";
                }
            }
        }
        $sender_info = getSenderInfo($consignment['sender_id'], $conn);
        $receiver_info = getReceiverInfo($consignment['receiver_id'], $conn);
        $dispatcher_info = getDispatcherInfo($consignment['dispatcher_id'], $conn);
        ?>
        <style>
            .wrapper {
                max-width: 1000px;
                margin: 0 auto;
                padding: 20px;
                background: #f9f9f9;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            header {
                font-size: 24px;
                margin-bottom: 20px;
                font-weight: bold;
            }
            .flex-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 20px;
            }
            .flex-item {
                flex: 1;
                min-width: 200px;
                background: #fff;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            }
            .flex-item header {
                font-size: 18px;
                margin-bottom: 10px;
            }
            .flex-item p {
                margin: 5px 0;
            }
            .btnText {
                font-size: 16px;
            }
            .backBtn, .nextBtn {
                display: inline-flex;
                align-items: center;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 10px 15px;
                cursor: pointer;
                text-decoration: none;
            }
            .backBtn:hover, .nextBtn:hover {
                background-color: #0056b3;
            }
            .backBtn i, .nextBtn i {
                margin-left: 10px;
            }
            .container {
                margin-top: 20px;
            }
        </style>
        <div class="wrapper">
            <header>Consignment Status</header>
            <div class="flex-container">
                <div class="flex-item">
                    <p>Tracking Number: <span><?php echo htmlspecialchars($consignment['tracking_number']); ?></span></p>
                    <p>Delivery Status: <span><?php echo htmlspecialchars($consignment['delivery_status']); ?></span></p>
                </div>
            </div>
            <div class="flex-container">
                <div class="flex-item">
                    <header>Sender Information</header>
                    <?php if ($sender_info): ?>
                        <p>Sender Name: <span><?php echo htmlspecialchars($sender_info['first_name'] . ' ' . $sender_info['last_name']); ?></span></p>
                        <p>Sender Email: <span><?php echo htmlspecialchars($sender_info['email']); ?></span></p>
                        <p>Sender Phone: <span><?php echo htmlspecialchars($sender_info['phone']); ?></span></p>
                    <?php endif; ?>
                </div>
                <div class="flex-item">
                    <header>Receiver Information</header>
                    <?php if ($receiver_info): ?>
                        <p>Receiver Name: <span><?php echo htmlspecialchars($receiver_info['first_name'] . ' ' . $receiver_info['last_name']); ?></span></p>
                        <p>Receiver Email: <span><?php echo htmlspecialchars($receiver_info['email']); ?></span></p>
                        <p>Receiver Phone: <span><?php echo htmlspecialchars($receiver_info['phone']); ?></span></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex-container">
                <div class="flex-item">
                    <header>Dispatcher Information</header>
                    <p>Dispatcher Status: <span><?php echo !empty($consignment['dispatcher_id']) ? 'Available' : 'Please wait for courier confirmation.'; ?></span></p>
                    <?php if ($dispatcher_info): ?>
                        <p>Dispatcher Name: <span><?php echo htmlspecialchars($dispatcher_info['first_name'] . ' ' . $dispatcher_info['last_name']); ?></span></p>
                        <p>Dispatcher Email: <span><?php echo htmlspecialchars($dispatcher_info['email']); ?></span></p>
                        <p>Dispatcher Phone: <span><?php echo htmlspecialchars($dispatcher_info['phone_number']); ?></span></p>
                    <?php endif; ?>
                    <p>Dispatcher Pickup Status: <span><?php echo htmlspecialchars($consignment['dispatcher_pickup_status']); ?></span></p>
                </div>
            </div>

            <header>Addresses</header>
            <div class="flex-container">
                <div class="flex-item">
                    <p>Pickup Address: <span><?php echo htmlspecialchars($consignment['pickup_address']); ?></span></p>
                </div>
                <div class="flex-item">
                    <p>Delivery Address: <span><?php echo htmlspecialchars($consignment['delivery_address']); ?></span></p>
                </div>
            </div>
            <div class="flex-container">
                <div class="flex-item">
                    <header>Timestamps</header>
                    <p>Created At: <span><?php echo htmlspecialchars($consignment['created_at']); ?></span></p>
                    <p>Updated At: <span><?php echo htmlspecialchars($consignment['updated_at']); ?></span></p>
                </div>
                <div class="flex-item">
                    <header>Delivery Proof</header>
                    <?php if ($consignment['delivery_proof'] != ''): ?>
                        <p>Proof of Delivery: <a href="<?php echo htmlspecialchars($consignment['delivery_proof']); ?>" target="_blank">View Proof</a></p>
                    <?php else: ?>
                        <p>No proof of delivery available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="container">
                <form method="post">
                    <button type="submit" name="request_dispatcher" class="btnText">Request Dispatcher <i class="fas fa-truck"></i></button>
                </form>
                <a href="shipping_status.php" class="backBtn">Back <i class="fas fa-arrow-left"></i></a>
                <!-- <a href="next_page.php" class="nextBtn">Next <i class="fas fa-arrow-right"></i></a> -->
            </div>
        </div>
        <?php
    } else {
        echo "<script>
                alert('No consignment found with the given tracking number.');
                window.location.href = 'shipping_status.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Tracking number is required.');
            window.location.href = 'shipping_status.php';
          </script>";
}

// function getSenderInfo($sender_id, $conn) {
//     $sql = "SELECT * FROM senders WHERE sender_id = '$sender_id'";
//     $result = mysqli_query($conn, $sql);
//     return $result ? mysqli_fetch_assoc($result) : null;
// }

// function getReceiverInfo($receiver_id, $conn) {
//     $sql = "SELECT * FROM receivers WHERE receiver_id = '$receiver_id'";
//     $result = mysqli_query($conn, $sql);
//     return $result ? mysqli_fetch_assoc($result) : null;
// }

// function getDispatcherInfo($dispatcher_id, $conn) {
//     $sql = "SELECT * FROM courier_dispatchers WHERE dispatcher_id = '$dispatcher_id'";
//     $result = mysqli_query($conn, $sql);
//     return $result ? mysqli_fetch_assoc($result) : null;
// }

include('includes/footer.php');
?>
