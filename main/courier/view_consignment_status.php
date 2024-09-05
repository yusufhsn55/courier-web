<?php
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['d_ID']) && isset($_POST['tracking_number'])) {
    $dispatcher_id = mysqli_real_escape_string($conn, $_POST['d_ID']);
    $tracking_number = mysqli_real_escape_string($conn, $_POST['tracking_number']);

   
    if (isset($_POST['confirm_delivery'])) {
        $p_status = "delivered";

        
        if (isset($_FILES['delivery_proof']) && $_FILES['delivery_proof']['error'] == 0) {
           
            $uploadDir = 'uploads/';
            
            $fileName = uniqid() . '_' . basename($_FILES['delivery_proof']['name']);
            $filePath = $uploadDir . $fileName;

           
            $fileType = mime_content_type($_FILES['delivery_proof']['tmp_name']);
            if ($fileType == 'application/pdf') {
                
                if (move_uploaded_file($_FILES['delivery_proof']['tmp_name'], $filePath)) {
                   
                    $sql_update = "UPDATE consignments SET delivery_status = '$p_status', delivery_proof = '$filePath' WHERE tracking_number = '$tracking_number' AND dispatcher_id = '$dispatcher_id'";
                    if (mysqli_query($conn, $sql_update)) {
                        echo "<script>
                                alert('Delivery confirmed successfully!');
                                window.location.href = 'completed_dispatchers.php';
                              </script>";
                    } else {
                        echo "<script>
                                alert('Error in confirming delivery.');
                                window.location.href = 'view_consignment_status.php?tracking_number=' + encodeURIComponent('$tracking_number');
                              </script>";
                    }
                } else {
                    echo "<script>alert('Failed to upload the PDF file.');</script>";
                }
            } else {
                echo "<script>alert('Only PDF files are allowed.');</script>";
            }
        } else {
            echo "<script>alert('No file was uploaded or there was an error uploading the file.');</script>";
        }
    } else {
        
        $d_status = "confirmed";
        $p_status = "in_transit";

        $sql_update = "UPDATE consignments SET dispatcher_id = '$dispatcher_id', dispatcher_pickup_status = '$d_status', delivery_status = '$p_status' WHERE tracking_number = '$tracking_number'";
        if (mysqli_query($conn, $sql_update)) {
            echo "<script>
                    alert('Dispatcher ID updated successfully!');
                    window.location.href = 'delivery_status.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error in updating dispatcher ID.');
                    window.location.href = 'view_consignment_status.php?tracking_number=' + encodeURIComponent('$tracking_number');
                  </script>";
        }
    }
}

if (isset($_POST['tracking_number']) && !empty($_POST['tracking_number'])) {
    $tracking_number = mysqli_real_escape_string($conn, $_POST['tracking_number']);

    $sql = "SELECT * FROM consignments WHERE tracking_number = '$tracking_number'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $consignment = mysqli_fetch_assoc($result);

        $sender_info = getSenderInfo($consignment['sender_id'], $conn);
        $receiver_info = getReceiverInfo($consignment['receiver_id'], $conn);
        $dispatcher_info = getDispatcherInfo($consignment['dispatcher_id'], $conn);
        ?>
        <div class="wrapper">
            <div id='consignmentNote' class="container col-75">
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
                        <p>Dispatcher Status(Courier): <span>
                            <?php 
                                $available = $consignment['dispatcher_id'];  
                                if (isset($available)) {
                                    echo "Available";
                                } else {
                                    echo "Please wait for courier confirmation.";
                                }
                            ?>
                        </span></p>
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
                    <button class="backBtn" onclick="window.open('<?php echo htmlspecialchars($consignment['delivery_proof']); ?>', '_blank')"><span class="btnText">
                        View Proof of Delivery</span>
                    </button>
                <?php } else { ?>
                    <p>No proof of delivery uploaded.</p>
                <?php } ?>
                    </div>
                    
                </div>
               
                
                
                        </div>

            <div class="container col-25">
                <header>Actions</header>
                <?php
                $available = $consignment['dispatcher_id']; 
                $statusPickup = $consignment['dispatcher_pickup_status'];
                $statusDelivery = $consignment['delivery_status'];

                if (isset($available) && $statusPickup == 'confirmed' && $statusDelivery == 'in_transit') {
                    ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form first">
                            <div class="details personal">
                                <div class="fields">
                                    <div class="input-field">
                                        <input type="hidden" name="d_ID" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" required>
                                        <input type="hidden" name="tracking_number" value="<?php echo htmlspecialchars($tracking_number); ?>">
                                        <input type="hidden" name="confirm_delivery" value="true">
                                    </div>
                                    <div class="input-field special">
                                        <label>Upload Delivery Proof (PDF):</label>
                                        <input type="file" name="delivery_proof" id="delivery_proof" accept="application/pdf" required>
                                    </div>
                                </div>
                                <button type="submit" class="nextBtn">
                                    <span class="btnText">Confirm Delivery</span>
                                    <i class="uil uil-navigator"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php
                } elseif ($statusDelivery == 'delivered') {
                    ?>
                    <form action="" method="POST">
                        <div class="form first">
                            <div class="details personal">
                                <div class="fields">
                                    <div class="input-field">
                                        <input type="hidden" name="d_ID" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" required>
                                        <input type="hidden" name="tracking_number" value="<?php echo htmlspecialchars($tracking_number); ?>">
                                    </div>
                                </div>
                                <button type="submit" class="nextBtn" onclick="printConsignmentNote()">
                                    <span class="btnText">Print Receipt</span>
                                    <i class="uil uil-navigator"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <form action="" method="POST">
                        <div class="form first">
                            <div class="details personal">
                                <div class="fields">
                                    <div class="input-field">
                                        <input type="hidden" name="d_ID" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" required>
                                        <input type="hidden" name="tracking_number" value="<?php echo htmlspecialchars($tracking_number); ?>">
                                    </div>
                                </div>
                                <button type="submit" class="nextBtn">
                                    <span class="btnText">Confirm Pickup</span>
                                    <i class="uil uil-navigator"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        echo "<p>No consignment found with the provided tracking number.</p>";
    }
} else {
    echo "<p>No tracking number provided.</p>";
}

include('includes/footer.php');
?>

<script>
function printConsignmentNote() {
    var printContents = document.getElementById('consignmentNote').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
