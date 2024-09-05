<?php
include('includes/header.php'); 
error_reporting(E_ALL);

$package = null;
$recipient = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['package_id']) && !empty($_POST['package_id']) && isset($_POST['recipient_id']) && !empty($_POST['recipient_id'])) {
        $packageId = mysqli_real_escape_string($conn, $_POST['package_id']);
        $recipientId = mysqli_real_escape_string($conn, $_POST['recipient_id']);

     
        $sqlPackage = "SELECT * FROM packages WHERE id = '$packageId'";
        $resultPackage = mysqli_query($conn, $sqlPackage);

        $sqlRecipient = "SELECT * FROM customers WHERE id = '$recipientId'";
        $resultRecipient = mysqli_query($conn, $sqlRecipient);

        if ($resultPackage && $resultRecipient && mysqli_num_rows($resultPackage) > 0 && mysqli_num_rows($resultRecipient) > 0) {
            $package = mysqli_fetch_assoc($resultPackage);
            $recipient = mysqli_fetch_assoc($resultRecipient);

            if (isset($_POST['pickup_address']) && isset($_POST['delivery_address'])) {
                $shipperaddress = mysqli_real_escape_string($conn, $_POST['pickup_address']);
                $receiver_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
                $tracking_number = uniqid('TRK-', true);

                $sqlInsert = "INSERT INTO consignments 
                            (package_id, tracking_number, sender_id, receiver_id, dispatcher_id, dispatcher_pickup_status, delivery_status, pickup_address, delivery_address, created_at, updated_at, delivery_proof) 
                            VALUES 
                            ('$packageId','$tracking_number', '{$package['customer_id']}', '$recipientId', NULL, 'pending', 'created', '$shipperaddress', '$receiver_address', NOW(), NOW(), 'pending')";
                
                // Debugging: echo the insert query
                echo "Insert Query: " . $sqlInsert . "<br>";

                if (mysqli_query($conn, $sqlInsert)) {
             
                    echo "<script>
                            alert('Consignment added successfully!');
                            window.location.href = 'view_consignment_status.php?tracking_number=$tracking_number';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error adding consignment: " . mysqli_error($conn) . "');
                            window.location.href = 'all_packages.php';
                          </script>";
                }

                mysqli_close($conn);
            }
        } else {
            echo "<p>No package or recipient found with the provided IDs.</p>";
        }
    } else {
        echo "<p>No package or recipient ID provided.</p>";
    }
}
?>

<div class="container">
    <header>Add Consignment Note</header>

    <form action="" method="POST">
        <div class="form">
            <div class="details">
                <span class="title">Consignment Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Package</label>
                        <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package['id']); ?>" required>
                        <input type="text"  value="<?php echo htmlspecialchars($package['product_name']); ?>" required>
                    </div>
                    <?php if ($package && $recipient): ?>
                    <div class="input-field">
                        <label>Sender Name</label>
                        <input type="hidden" value="<?php echo htmlspecialchars($package['customer_id']); ?>" readonly>
                        <input type="text" value="<?php echo htmlspecialchars(getCustomersFullName($user_id, $conn)); ?>" readonly>
                    </div>
                    <div class="input-field">
                        <label>PickUp Address</label>
                        <textarea name="pickup_address" placeholder="Enter Physical location" required></textarea>
                    </div>
                    <div class="input-field">
                        <label>Delivery Address</label>
                        <textarea name="delivery_address" placeholder="Enter Physical location" required></textarea>
                       
                    </div>
                    <div class="input-field">
                        <input type="hidden" name="recipient_id" value="<?php echo htmlspecialchars($recipient['id']); ?>" required>
                    </div>
                    <?php endif; ?>
                </div>
               
                <div class="buttons">
                        <button type="submit" class="submit">
                            <span class="btnText">Generate Consignment</span>
                        </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>
