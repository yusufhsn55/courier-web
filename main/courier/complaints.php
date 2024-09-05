<?php
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dispatchStaff = $_SESSION['user_id']; 
    $con_id = $_POST['con_id'];
    $complaintText = $_POST['complaint_text'];

    // Insert complaint into the database
    $sql = "INSERT INTO complaints (customer_id, consignment_id, complaint_text, status, created_at) 
            VALUES ('$dispatchStaff', '$con_id', '$complaintText', 'Open', NOW())";
    if (mysqli_query($conn, $sql)) {
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
}

$dispatchStaff = $_SESSION['user_id'];
$complaintsSql = "SELECT * FROM complaints WHERE customer_id = '$dispatchStaff'";
$complaintsResult = mysqli_query($conn, $complaintsSql);

?>

<div class="container">
    <header>Submit a Complaint</header>

    <form action="" method="POST">
        <div class="form">
            <div class="details">
                <div class="fields">
                    <div class="input-field">
                        <label>Package</label>
                        <?php 
                            $receiverID = $_SESSION['user_id'];
                        ?>
                        <input type="hidden" name="complaint" value="<?php echo htmlspecialchars($receiverID); ?>" required/>
                        <select name="con_id" required>
                            <option value="" disabled selected>Select a Package</option>
                           
                            <?php 
                                $sql = "SELECT * FROM consignments WHERE dispatcher_id = '$receiverID'";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $con_id = htmlspecialchars($row["consignment_id"]);
                                        $packageID = htmlspecialchars($row["package_id"]);
                                        $trackNo = htmlspecialchars($row["tracking_number"]);
                                        $sender = htmlspecialchars($row["sender_id"]);
                                        $packageName = getPackageInfo($packageID, $conn);
                                        $senderName = getSenderInfo($sender, $conn);

                                        echo '<option value="' . $con_id . '">'. $packageName['product_name'] . '-( ' . $trackNo . ')</option>';
                                    }
                                } else {
                                    echo '<option value="None">No Packages Available</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Complaint Text</label>
                        <textarea name="complaint_text" placeholder="Describe your complaint" required></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="submitBtn">
                <span class="btnText">Submit Complaint</span>
            </button>
        </div>
    </form>

    <div class="complaints-list">
        <header>Your Complaints</header>
        <?php if (mysqli_num_rows($complaintsResult) > 0): ?>
            
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
                        
                        while ($row = mysqli_fetch_assoc($complaintsResult)): ?>
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

<?php
include('includes/footer.php'); 
?>
