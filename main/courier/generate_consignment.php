<?php
include('includes/header.php'); 


$package = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['package_id']) && !empty($_POST['package_id'])) {
        $packageId = mysqli_real_escape_string($conn, $_POST['package_id']);

        // Fetch package details
        $sql = "SELECT * FROM packages WHERE id = '$packageId'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $package = mysqli_fetch_assoc($result);

            // Retrieve additional form data if they are provided
            if (isset($_POST['pickup_address']) && isset($_POST['delivery_address']) && isset($_POST['receiver_id'])) {
                $shipperaddress = mysqli_real_escape_string($conn, $_POST['pickup_address']);
                $receiver_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
                $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);
              
                // Generate a tracking number
                $tracking_number = uniqid('TRK-', true);

                // Insert data into consignments table
                $sql = "INSERT INTO consignments 
                        (tracking_number, sender_id, receiver_id, dispatcher_id, dispatcher_pickup_status, delivery_status, pickup_address, delivery_address, created_at, updated_at, delivery_proof) 
                        VALUES 
                        ('$tracking_number', '{$package['customer_id']}', '$receiver_id', NULL, 'pending', 'created', '$shipperaddress', '$receiver_address', NOW(), NOW(), 'pending')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('Consignment added successfully!');
                            window.location.href = 'view_consignment_status.php';
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
            echo "<p>No package found with the provided ID.</p>";
        }
    } else {
        echo "<p>No package ID provided.</p>";
    }
}
?>

<div class="container">
    <header>Add Consignment</header>

    <form action="" method="POST">
        <div class="form">
            <div class="details">
                <span class="title">Consignment Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Package ID</label>
                        <input type="text" name="package_id"  value="<?php echo htmlspecialchars($package['id']); ?>" required>
                    </div>
                    <?php if ($package): ?>
                    <div class="input-field">
                        <label>Sender Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($package['customer_id']); ?>" readonly>
                    </div>
                    <div class="input-field">
                        <label>PickUp Address</label>
                        <textarea name="pickup_address" placeholder="Enter Physical location" required></textarea>
                    </div>
                    <div class="input-field">
                        <label>Delivery Address</label>
                        <input type="text" name="delivery_address" placeholder="Enter Physical Location" required>
                    </div>
                    <div class="input-field">
                        <label>Receiver</label>
                        <select name="receiver_id" required>
                            <option value="None" selected>Unregistered</option>
                            <?php 
                            $sql = "SELECT * FROM receiver";
                            $result = mysqli_query($conn, $sql);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $id = $row["id"];
                                    $firstname = htmlspecialchars($row["first_name"]);
                                    $lastname = htmlspecialchars($row["last_name"]);
                                    echo '<option value="' . $id . '">' . $firstname . ' ' . $lastname . '</option>';
                                }
                            } else {
                                echo '<option value="None">No Receivers Available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="submitBtn">
                    <span class="btnText">Generate Consignment</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>
