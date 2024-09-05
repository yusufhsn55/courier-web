<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}

// Handle complaint reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $complaintId = $_POST['complaint_id'];
    $replyText = $_POST['reply_text'];

    // Update the complaint with the reply
    $sql = "UPDATE complaints SET status = 'Replied', updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $complaintId);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Reply submitted successfully!');
                    window.location.href = 'view_complaints.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error submitting reply.');
                    window.location.href = 'view_complaints.php';
                  </script>";
        }

        $stmt->close();
    } else {
        die("Error preparing SQL statement.");
    }
}

// Retrieve all users for selection
$usersSql = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM customers";
$usersResult = $conn->query($usersSql);

if (!$usersResult) {
    die("Error retrieving users.");
}

// Retrieve complaints for a selected user
$complaintsResult = [];
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $complaintsSql = "SELECT * FROM complaints WHERE customer_id = ?";
    $complaintsStmt = $conn->prepare($complaintsSql);
    if ($complaintsStmt) {
        $complaintsStmt->bind_param("i", $userId);
        $complaintsStmt->execute();
        $complaintsResult = $complaintsStmt->get_result();
        $complaintsStmt->close();
    } else {
        die("Error preparing SQL statement for complaints.");
    }
}
?>

<div class="container">
    <header>View and Reply to Complaints</header>

    <form action="view_complaints.php" method="GET">
        <div class="form">
            <div class="input-field">
                <label>Select User</label>
                <select name="user_id" onchange="this.form.submit()" required>
                    <option value="" disabled selected>Select a user</option>
                    <?php while ($user = $usersResult->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($user['id']); ?>">
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if (isset($_GET['user_id']) && $complaintsResult->num_rows > 0): ?>
        <div class="complaints-list">
            <header>Complaints</header>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Subject</th>
                        <th>Complaint Text</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                    while ($row = $complaintsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php $i++; ?></td>
                            <td><?php 
                            
                          
                             $consignment = $row['consignment_id'];
                             $sqlConsignment = 
                             "SELECT * FROM consignments WHERE consignment_id = '$consignment'";
                             $consignmentResult = $conn->query($sqlConsignment);
                             $consignmentRow = $consignmentResult->fetch_assoc();
                             
                             $nameConsignment = getPackageInfo($consignmentRow['package_id'], $conn);
                             echo $nameConsignment['product_name'];
                            
                             ?></td>
                            <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'Open'): ?>
                                    <button class="backBtn" data-id="<?php echo htmlspecialchars($row['id']); ?>"><span class="btnText">
                                        Reply
                                </span>
                                    </button>
                                <?php else: ?>
                                    <span>Replied</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($_GET['user_id'])): ?>
        <p>No complaints found for this user.</p>
    <?php endif; ?>
</div>

<div id="replyModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reply to Complaint</h2>
        <form id="replyForm" action="view_complaints.php" method="POST">
            <input type="hidden" name="complaint_id" id="complaint_id">
            <textarea name="reply_text" placeholder="Write your reply here..." required></textarea>
            <button type="submit" name="reply">Submit Reply</button>
        </form>
    </div>
</div>

<script>
    // Modal handling
    var modal = document.getElementById("replyModal");
    var closeBtn = document.getElementsByClassName("close")[0];

    document.querySelectorAll(".replyBtn").forEach(function(button) {
        button.onclick = function() {
            var complaintId = this.getAttribute("data-id");
            document.getElementById("complaint_id").value = complaintId;
            modal.style.display = "block";
        };
    });

    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>

<?php include('includes/footer.php'); ?>
