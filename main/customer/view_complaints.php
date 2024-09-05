<?php
include('includes/header.php');

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
//=>
$usersResult = $conn->query($usersSql);
//=>
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
    <header class="page-header">
        <h1>View and Reply to Complaints</h1>
    </header>

    <form action="view_complaints.php" method="GET" class="user-selection-form">
        <div class="form-group">
            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id" onchange="this.form.submit()" required>
                <option value="" disabled selected>Select a user</option>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($user['id']); ?>">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <?php if (isset($_GET['user_id']) && $complaintsResult->num_rows > 0): ?>
        <div class="complaints-list">
            <header class="section-header">
                <h2>Complaints</h2>
            </header>
            <table class="table">
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <!-- <th>Package ID</th> -->
                        <th>Complaint Text</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $complaintsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'Open'): ?>
                                    <button class="btn replyBtn" data-id="<?php echo htmlspecialchars($row['id']); ?>">
                                        Reply
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
            <button type="submit" name="reply" class="btn submit-reply-btn">Submit Reply</button>
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

<style>
.container {
    padding: 20px;
    font-family: Arial, sans-serif;
}

.page-header {
    margin-bottom: 20px;
}

.page-header h1 {
    font-size: 24px;
    font-weight: bold;
}

.user-selection-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.complaints-list {
    margin-top: 20px;
}

.section-header {
    margin-bottom: 15px;
}

.section-header h2 {
    font-size: 20px;
    font-weight: bold;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

.table thead {
    background-color: #f8f9fa;
}

.table th,
.table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.table tr:nth-of-type(even) {
    background-color: #f2f2f2;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    transition: background-color 0.3s;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

.modal {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 5px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

textarea {
    width: 100%;
    height: 150px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    margin-bottom: 20px;
}

.submit-reply-btn {
    background-color: #28a745;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    color: #fff;
    border-radius: 5px;
}

.submit-reply-btn:hover {
    background-color: #218838;
}
</style>
