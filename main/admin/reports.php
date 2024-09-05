<?php
include('includes/header.php');

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
                    $dispatcher_name = $dispatcher['first_name'] . ' ' . $dispatcher['last_name'];

                    $sql_update_dispatcher = "UPDATE consignments SET dispatcher_id = '$dispatcher_id' WHERE tracking_number = '$tracking_number'";
                    if (mysqli_query($conn, $sql_update_dispatcher)) {
                        echo "<script>
                                alert('Dispatcher request sent successfully! The selected dispatcher is $dispatcher_name.');
                                window.location.href = 'shipping_status.php';
                              </script>";
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

        // Fetch related information
        $sender_info = getSenderInfo($consignment['sender_id'], $conn);
        $receiver_info = getReceiverInfo($consignment['receiver_id'], $conn);
        $dispatcher_info = getDispatcherInfo($consignment['dispatcher_id'], $conn);
        ?>
        <div class="wrapper">
            <div id="consignmentNote" class="container">
                <header>Consignment Status</header>

                <div class="flex-container">
                    <div class="flex-item">
                        <p>Tracking Number: <span><?php echo htmlspecialchars($consignment['tracking_number']); ?></span></p>
                    </div>
                    <div class="flex-item">
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
                        <p>Dispatcher Status (Courier): <span><?php echo !empty($consignment['dispatcher_id']) ? "Available" : "Please wait for courier confirmation."; ?></span></p>
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
                        <?php if ($consignment['delivery_proof'] != 'pending' && !empty($consignment['delivery_proof'])) { ?>
                            <button class="backBtn" onclick="window.open('<?php echo htmlspecialchars($consignment['delivery_proof']); ?>', '_blank')"><span class="btnText">View Proof of Delivery</span></button>
                        <?php } else { ?>
                            <p>No proof of delivery uploaded.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php 
            $available = $consignment['dispatcher_id'];  
            $statusReceiver = $consignment['receiver_id'];

            if (isset($available) && $statusReceiver == $_SESSION['user_id']) {
                ?>
                <div class="container">
                    <header>Confirm Delivery</header>
                    <form action="" method="POST">
                        <input type="hidden" name="d_ID" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" required>
                        <input type="hidden" name="tracking_ID" value="<?php echo htmlspecialchars($tracking_number); ?>" required>
                        <button type="submit" name="confirm_delivery" class="submitBtn"><span class="btnText">Confirm Delivery</span></button>
                    </form>
                </div>
                <?php 
            } else {
                ?>
                <div class="container">
                    <header>Request Dispatcher</header>
                    <form action="" method="POST">
                        <button type="submit" name="request_dispatcher" class="submitBtn"><span class="btnText">Request Dispatcher</span></button>
                    </form>
                </div>
                <?php 
            }
            ?>
            <button onclick="printConsignmentNote()" class="backBtn"><span class="btnText">Print Consignment Report</span></button>
        </div>

        <script>
            function printConsignmentNote() {
                var divContents = document.getElementById("consignmentNote").innerHTML;
                var a = window.open('', '', 'height=500, width=500');
                a.document.write('<html><head><title>Consignment Note</title></head>');
                a.document.write('<body>');
                a.document.write(divContents);
                a.document.write('</body></html>');
                a.document.close();
                a.print();
            }
        </script>

        <?php
    } else {
        echo "<script>
                alert('Invalid tracking number.');
                window.location.href = 'shipping_status.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Please provide a tracking number.');
            window.location.href = 'shipping_status.php';
          </script>";
}

include('includes/footer.php');

// Helper functions to fetch sender, receiver, and dispatcher information
function getSenderInfo($sender_id, $conn) {
    $sql = "SELECT first_name, last_name, email, phone FROM users_tbl WHERE user_id = '$sender_id'";
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_assoc($result) : null;
}

function getReceiverInfo($receiver_id, $conn) {
    $sql = "SELECT first_name, last_name, email, phone FROM users_tbl WHERE user_id = '$receiver_id'";
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_assoc($result) : null;
}

function getDispatcherInfo($dispatcher_id, $conn) {
    $sql = "SELECT first_name, last_name, email, phone_number FROM courier_dispatchers WHERE dispatcher_id = '$dispatcher_id'";
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_assoc($result) : null;
}
?>
