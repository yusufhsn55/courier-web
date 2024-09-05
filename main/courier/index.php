<?php
include('includes/header.php');

$courier_id = $_SESSION['user_id'];

date_default_timezone_set('Africa/Nairobi');
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$currentDayOfWeek = date('l'); 

// Retrieve notifications
$notificationsSql = "SELECT * FROM notifications";
$notificationsResult = mysqli_query($conn, $notificationsSql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dispatcher_id'], $_POST['status'])) {
    $dispatcher_id = intval($_POST['dispatcher_id']);
    $status = $_POST['status'] === 'Active' ? 'Active' : 'Inactive';

    $updateStatusSql = "UPDATE courier_dispatchers SET status = ? WHERE dispatcher_id = ?";
    $stmt = $conn->prepare($updateStatusSql);
    if ($stmt) {
        $stmt->bind_param('si', $status, $dispatcher_id);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Dispatcher status updated successfully!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating dispatcher status.');
                    window.location.href = 'index.php';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing SQL statement.');
                window.location.href = 'index.php';
              </script>";
    }
}

// Get current dispatcher status
$statusSql = "SELECT status FROM courier_dispatchers WHERE dispatcher_id = ?";
$stmt = $conn->prepare($statusSql);
if ($stmt) {
    $stmt->bind_param('i', $courier_id);
    $stmt->execute();
    $statusResult = $stmt->get_result();
    if ($statusResult && $statusResult->num_rows > 0) {
        $statusRow = $statusResult->fetch_assoc();
        $status = $statusRow['status'];
        $status_message = $status === 'Active' ? 'Available' : ($status === 'Inactive' ? 'Inactive' : 'Courier Suspended');
        $status_action = $status === 'Active' ? 'Inactive' : 'Active';
    } else {
        $status_message = 'Courier Suspended';
        $status_action = 'Active'; 
    }
    $stmt->close();
} else {
    echo "<script>
            alert('Error preparing SQL statement for status.');
            window.location.href = 'index.php';
          </script>";
}

?>

<div class="overview-boxes">
    <div class="box">
        <div class="right-side">
            <div class="box-topic">Today's: <?php echo htmlspecialchars($currentDayOfWeek); ?></div>
            <div class="number"><?php echo htmlspecialchars($currentDate); ?></div>
            <div class="indicator">
                <span class="text">Time: <?php echo htmlspecialchars($currentTime); ?> (Hrs)</span>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="right-side">
            <div class="box-topic">Rider Status</div>
            <div class="number"><?php echo htmlspecialchars($status_message); ?></div>
        </div>
    </div>
    <div class="box">
        <div class="right-side">
            <div class="box-topic">Action</div>
            <div class="number">
                <form action="index.php" method="post">
                    <input type="hidden" name="dispatcher_id" value="<?php echo htmlspecialchars($courier_id); ?>">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_action); ?>">
                    <button type="submit" class="backBtn">
                        <span class="btnText"><?php echo htmlspecialchars($status_action === 'Active' ? 'Set to Inactive' : 'Set to Active'); ?></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="wrapper">
    <div class="container col-75">
        <header>Notification</header>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name (client)</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($notificationsResult) {
                        if (mysqli_num_rows($notificationsResult) > 0) {
                            while ($row = mysqli_fetch_assoc($notificationsResult)) {
                                $customerName = getCustomersFullName($user_id, $conn);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($customerName) . "</td>";
                                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No notifications found.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Error: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container col-25">
        <header>Delivery Status</header>
        <div class="left-box box">
            <ul class="detail">
                <li>
                    <a href="#">
                        <img src="assets/img/icons/delivery-courier.png" alt="" />
                        <span class="product">Pending Dispatchers</span>
                    </a>
                    <span class="number-title"><?php echo htmlspecialchars($totalPending); ?></span>
                </li>
                <li>
                    <a href="#">
                        <img src="assets/img/icons/delivery-courier.png" alt="" />
                        <span class="product">Completed Dispatchers</span>
                    </a>
                    <span class="number-title"><?php echo htmlspecialchars($totalDelivered); ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
