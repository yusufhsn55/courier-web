<?php
include('includes/header.php');


// Handle complaint reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $complaintId = mysqli_real_escape_string($conn, $_POST['complaint_id']);
    $replyText = mysqli_real_escape_string($conn, $_POST['reply_text']);

    // Update the complaint with the reply
    $sql = "UPDATE complaints SET status = 'Replied', updated_at = NOW() WHERE id = '$complaintId'";
    if (mysqli_query($conn, $sql)) {
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
}

// Retrieve all users for selection
$usersSql = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM customers";
$usersResult = mysqli_query($conn, $usersSql);

if (!$usersResult) {
    die("Error retrieving users.");
}

// Retrieve complaints for a selected user
$complaintsResult = [];
if (isset($_GET['user_id'])) {
    $userId = mysqli_real_escape_string($conn, $_GET['user_id']);

    $complaintsSql = "SELECT * FROM complaints WHERE customer_id = '$userId'";
    $complaintsResult = mysqli_query($conn, $complaintsSql);

    if (!$complaintsResult) {
        die("Error retrieving complaints.");
    }
}
?>
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
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
</style>
<div class="container">
    <header>View and Reply to Complaints</header>

    <form action="view_complaints.php" method="GET">
        <div class="form">
            <div class="input-field">
                <label>Select User</label>
                <select name="user_id" onchange="this.form.submit()" required>
                    <option value="" disabled selected>Select a user</option>
                    <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                        <option value="<?php echo htmlspecialchars($user['id']); ?>">
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if (isset($_GET['user_id']) && mysqli_num_rows($complaintsResult) > 0): ?>
        <div class="complaints-list">
            <header>Complaints</header>
            <table>
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Package ID</th>
                        <th>Complaint Text</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($complaintsResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['package_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'Open'): ?>
                                    <button class="replyBtn" data-id="<?php echo htmlspecialchars($row['id']); ?>">
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
            <input type="text" name="complaint_id" id="complaint_id">
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
