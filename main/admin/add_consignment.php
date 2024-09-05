<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $shippername = $_POST['shippername'];
    $shipperaddress = $_POST['shipperaddress'];
    $phone = $_POST['phone'];
    $material_description = $_POST['material_description'];
    $no_of_item = $_POST['no_of_item'];
    $branch_id = $_POST['branch_id'];
    $booked_at_branch = $_POST['booked_at_branch'];
    $branchlocation = $_POST['branchlocation'];
    $date_of_booking = $_POST['date_of_booking'];
    $destination = $_POST['destination'];
    $shipment_charge = $_POST['shipment_charge'];
    $total_weight = $_POST['total_weight'];
    $corporate_id = $_POST['corporate_id'];
    $receiver_name = $_POST['receiver_name'];
    $receiver_address = $_POST['receiver_address'];
    $receiver_phone = $_POST['receiver_phone'];
    $ccn = $_POST['ccn'];
    $booking_id = $_POST['booking_id'];

    // Insert data into consignment table
    $sql = "INSERT INTO consignment 
            (shippername, shipperaddress, phone, material_description, no_of_item, branch_id, booked_at_branch, branchlocation, date_of_booking, destination, shipment_charge, total_weight, corporate_id, receiver_name, receiver_address, receiver_phone, ccn, booking_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssisiissssssisssi", $shippername, $shipperaddress, $phone, $material_description, $no_of_item, $branch_id, $booked_at_branch, $branchlocation, $date_of_booking, $destination, $shipment_charge, $total_weight, $corporate_id, $receiver_name, $receiver_address, $receiver_phone, $ccn, $booking_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Consignment added successfully!');
                    window.location.href = 'all_consignments.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error adding consignment.');
                    window.location.href = 'add_consignment.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing statement.');
                window.location.href = 'add_consignment.php';
              </script>";
    }

    $conn->close();
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
                        <label>Shipper Name</label>
                        <input type="text" name="shippername" placeholder="Enter shipper name" required>
                    </div>
                    <div class="input-field">
                        <label>Shipper Address</label>
                        <input type="text" name="shipperaddress" placeholder="Enter shipper address" required>
                    </div>
                    <div class="input-field">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Enter phone number" required>
                    </div>
                    <div class="input-field">
                        <label>Material Description</label>
                        <input type="text" name="material_description" placeholder="Enter material description" required>
                    </div>
                    <div class="input-field">
                        <label>Number of Items</label>
                        <input type="number" name="no_of_item" placeholder="Enter number of items" required>
                    </div>
                    <div class="input-field">
                        <label>Branch ID</label>
                        <input type="number" name="branch_id" placeholder="Enter branch ID" required>
                    </div>
                    <div class="input-field">
                        <label>Booked at Branch</label>
                        <input type="text" name="booked_at_branch" placeholder="Enter booked at branch" required>
                    </div>
                    <div class="input-field">
                        <label>Branch Location</label>
                        <input type="text" name="branchlocation" placeholder="Enter branch location" required>
                    </div>
                    <div class="input-field">
                        <label>Date of Booking</label>
                        <input type="date" name="date_of_booking" required>
                    </div>
                    <div class="input-field">
                        <label>Destination</label>
                        <input type="text" name="destination" placeholder="Enter destination" required>
                    </div>
                    <div class="input-field">
                        <label>Shipment Charge</label>
                        <input type="text" name="shipment_charge" placeholder="Enter shipment charge" required>
                    </div>
                    <div class="input-field">
                        <label>Total Weight</label>
                        <input type="text" name="total_weight" placeholder="Enter total weight" required>
                    </div>
                    <div class="input-field">
                        <label>Corporate ID</label>
                        <input type="number" name="corporate_id" placeholder="Enter corporate ID" required>
                    </div>
                    <div class="input-field">
                        <label>Receiver Name</label>
                        <input type="text" name="receiver_name" placeholder="Enter receiver name" required>
                    </div>
                    <div class="input-field">
                        <label>Receiver Address</label>
                        <input type="text" name="receiver_address" placeholder="Enter receiver address" required>
                    </div>
                    <div class="input-field">
                        <label>Receiver Phone</label>
                        <input type="text" name="receiver_phone" placeholder="Enter receiver phone" required>
                    </div>
                    <div class="input-field">
                        <label>CCN</label>
                        <input type="text" name="ccn" placeholder="Enter CCN" required>
                    </div>
                    <div class="input-field">
                        <label>Booking ID</label>
                        <input type="number" name="booking_id" placeholder="Enter booking ID" required>
                    </div>
                </div>
                <button type="submit" class="submitBtn">
                    <span class="btnText">Add Consignment</span>
                    <i class="uil uil-navigator"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>
