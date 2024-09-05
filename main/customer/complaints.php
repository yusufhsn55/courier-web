<?php
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = getCustomerId($user_id, $conn); 
    $con_id = $_POST['con_id'];
    $complaintText = $_POST['complaint_text'];

    $sql = "INSERT INTO complaints (customer_id, consignment_id, complaint_text, status, created_at) VALUES (?, ?, ?, 'Open', NOW())";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iis", $customerId, $con_id, $complaintText);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Complaint submitted successfully!');
                    window.location.href = 'complaints.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error submitting complaint.');
                    window.location.href = 'complaints.php';
                  </script>";
        }
        $stmt->close();
    } else {
        die("Error preparing SQL statement.");
    }
}

$complaintsSql = "SELECT * FROM complaints WHERE customer_id = ?";
$complaintsStmt = $conn->prepare($complaintsSql);
if ($complaintsStmt) {
    $customerId = getCustomerId($user_id, $conn);  
    $complaintsStmt->bind_param("i", $customerId);
    $complaintsStmt->execute();
    $complaintsResult = $complaintsStmt->get_result();
} else {
    die("Error preparing SQL statement for complaints.");
}
?>

<div class="container">
    <div class="card profile-card">
        <h2>Submit a Complaint</h2>
        <form action="" method="POST">
            <div class="row">
                <div class="col">
                    <label for="con_id">Package ID</label>
                    <?php 
                        $receiverID = getCustomerId($user_id, $conn);
                    ?>
                    <input type="hidden" name="complaint" value="<?php echo $receiverID; ?>" required/>
                    <select id="con_id" name="con_id" required>
                        <option value="" disabled selected>Select a Package</option>
                        <?php 
                            $sql = "SELECT * FROM consignments WHERE receiver_id = ?";
                            $stmt = $conn->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param("i", $receiverID);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $con_id = htmlspecialchars($row["consignment_id"]);
                                        $packageID = htmlspecialchars($row["package_id"]);
                                        $trackNo = htmlspecialchars($row["tracking_number"]);
                                        $packageName = getPackageInfo($packageID, $conn);

                                        echo '<option value="' . $con_id . '">' . $packageName['product_name'] . '-( ' . $trackNo . ')</option>';
                                    }
                                } else {
                                    echo '<option value="None">No Receivers Available</option>';
                                }
                                $stmt->close();
                            } else {
                                echo '<option value="None">Error retrieving packages</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label for="complaint_text">Complaint Text</label>
                    <textarea id="complaint_text" name="complaint_text" placeholder="Describe your complaint" required></textarea>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn-submit">Submit Complaint</button>
            </div>
        </form>
    </div>

    <div class="card profile-card">
        <h2>Your Complaints</h2>
        <?php if ($complaintsResult->num_rows > 0): ?>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Package</th>
                            <th>Complaint Text</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $complaintsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['consignment_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No complaints found.</p>
        <?php endif; ?>
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
    select,
    textarea {
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

    .table-wrapper {
        overflow-x: auto;
    }

    .fl-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fl-table th,
    .fl-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .fl-table thead th {
        background-color: #f2f2f2;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .fl-table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
