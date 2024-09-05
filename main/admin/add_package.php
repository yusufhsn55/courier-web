<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customer_id = $_POST['customer_id'];
    $tracking_number = $_POST['tracking_number'];
    $description = $_POST['description'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $value = $_POST['value'];
    $status = $_POST['status'];

    // Insert data into packages table
    $sql_packages = "INSERT INTO packages (customer_id, tracking_number, description, weight, dimensions, value, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt_packages = $conn->prepare($sql_packages);
    $stmt_packages->bind_param("isssdsss", $customer_id, $tracking_number, $description, $weight, $dimensions, $value, $status);

    if ($stmt_packages->execute()) {
        echo "<script>
                alert('Package added successfully!');
                window.location.href = 'all_packages.php';
              </script>";
    } else {
        echo "<script>
                alert('Error in adding package.');
                window.location.href = 'add_packages.php';
              </script>";
    }

    // Close the statement
    $stmt_packages->close();

    // Close the connection
    $conn->close();
}
?>

<div class="container">
    <header>Add Package</header>

    <form action="" method="POST">
        <div class="form first">
            <div class="details personal">
                <span class="title">Package Details</span>
                <div class="fields">
                    <div class="input-field">
                        <label>Customer ID</label>
                        <input type="number" name="customer_id" placeholder="Enter customer ID" required>
                    </div>
                    <div class="input-field">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" placeholder="Enter tracking number" required>
                    </div>
                    <div class="input-field">
                        <label>Description</label>
                        <textarea name="description" placeholder="Enter description"></textarea>
                    </div>
                    <div class="input-field">
                        <label>Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" placeholder="Enter weight" required>
                    </div>
                    <div class="input-field">
                        <label>Dimensions</label>
                        <input type="text" name="dimensions" placeholder="Enter dimensions" required>
                    </div>
                    <div class="input-field">
                        <label>Value ($)</label>
                        <input type="number" step="0.01" name="value" placeholder="Enter value" required>
                    </div>
                    <div class="input-field">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Pending" selected>Pending</option>
                            <option value="Shipped">Shipped</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="nextBtn">
                    <span class="btnText">Add Package</span>
                    <i class="uil uil-navigator"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<?php include('includes/footer.php'); ?>
