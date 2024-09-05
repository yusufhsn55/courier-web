<?php
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = getCustomerId($user_id, $conn);

    // Sanitize input
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $product = mysqli_real_escape_string($conn, $_POST['product']);
    $dimensions = mysqli_real_escape_string($conn, $_POST['dimensions']);
    $value = mysqli_real_escape_string($conn, $_POST['value']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if ($customerId !== null) {
        $sql_packages = "INSERT INTO packages (customer_id, product_name, description, weight, dimensions, value, status) 
                         VALUES ($customerId, '$product', '$description', $weight, '$dimensions', '$value', '$status')";

        if (mysqli_query($conn, $sql_packages)) {
            echo "<script>
                    alert('Package added successfully!');
                    window.location.href = 'all_packages.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error in adding package: " . mysqli_error($conn) . "');
                    window.location.href = 'add_package.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid customer ID.');
                window.location.href = 'add_package.php';
              </script>";
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<div class="container">
    <header class="main-header">Package Details (Shipping)</header>

    <form action="" method="POST" class="package-form">
        <div class="card">
            <div class="card-header">
                <h2>Mailed By: <?php echo getCustomersFullName($user_id, $conn); ?></h2>
            </div>

            <div class="card-body">
                <h3 class="section-title">Package Details</h3>
                
                <div class="form-group">
                    <label for="product">Product Name</label>
                    <input type="text" id="product" name="product" placeholder="Enter Product Name" required>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" step="0.01" name="weight" placeholder="Enter weight" required>
                </div>

                <div class="form-group">
                    <label for="dimensions">Dimensions</label>
                    <input type="text" id="dimensions" name="dimensions" placeholder="Enter dimensions" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Enter description"></textarea>
                </div>

                <div class="form-group">
                    <label for="value">Billing Status</label>
                    <select id="value" name="value" required>
                        <option value="None" selected>Indicate</option>
                        <option value="Paid">Paid</option>
                        <option value="Pay on Delivery">Pay on Delivery</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Shipment Status</label>
                    <select id="status" name="status" required>
                        <option value="Pending" selected>Schedule Pickup</option>
                        <option value="Shipped">Shipped</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="btnText">Add Package</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .main-header {
        text-align: center;
        font-size: 28px;
        margin-bottom: 20px;
        color: #007bff;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .card-header h2 {
        font-size: 22px;
        margin-bottom: 15px;
        color: #333;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .section-title {
        font-size: 20px;
        color: #555;
        margin-bottom: 10px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    textarea {
        resize: vertical;
        height: 100px;
    }

    .btn-submit {
        background-color: #28a745;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 18px;
    }

    .btn-submit:hover {
        background-color: #218838;
    }

    .btnText {
        font-weight: bold;
    }
</style>
