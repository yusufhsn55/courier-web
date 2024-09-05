<?php
include('includes/header.php');

$package = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    $packageId = mysqli_real_escape_string($conn, $_POST['package_id']);

    $sql = "SELECT * FROM packages WHERE id = '$packageId'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $package = mysqli_fetch_assoc($result);
    } else {
        echo "<p>No package found with the provided ID.</p>";
    }
}
?>

<div class="container">
    <div class="card profile-card">
        <h2>Process Recipient</h2>
        <form action="generate_consignment.php" method="POST">
            <div class="row">
                <div class="col">
                    <label for="package_name">Package Name</label>
                    <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package['id'] ?? ''); ?>" required>
                    <input type="text" id="package_name" value="<?php echo htmlspecialchars($package['product_name'] ?? ''); ?>" readonly>
                </div>
                <div class="col">
                    <label for="package_status">Package Status</label>
                    <input type="text" id="package_status" value="<?php echo htmlspecialchars($package['status'] ?? ''); ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="recipient_id">Select Recipient</label>
                    <select id="recipient_id" name="recipient_id" required>
                        <option value="" disabled selected>Select a user</option>
                        <?php 
                            $sql = "SELECT * FROM customers";
                            $result = mysqli_query($conn, $sql);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $id = htmlspecialchars($row["id"]);
                                    $firstname = htmlspecialchars($row["first_name"]);
                                    $lastname = htmlspecialchars($row["last_name"]);
                                    echo "<option value='$id'>$firstname $lastname</option>";
                                }
                            } else {
                                echo '<option value="None">No Receivers Available</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn-submit">Submit</button>
                <a href="add_recipient.php" class="btn-submit">Add Recipient</a>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .col {
        flex: 1;
        margin-right: 10px;
    }

    .col:last-child {
        margin-right: 0;
    }

    .btn-submit {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }
</style>
