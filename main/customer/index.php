<?php
include('includes/header.php');

if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $userId = (int)$_GET['user_id'];

    if ($action == 'activate') {
        $status = 1;
    } elseif ($action == 'deactivate') {
        $status = 0;
    } else {
        die("Invalid action.");
    }

    $sql = "UPDATE users_tbl SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $status, $userId);
        if ($stmt->execute()) {
            echo "<script>
                    alert('User status updated successfully!');
                    window.location.href = 'dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating user status.');
                    window.location.href = 'dashboard.php';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing SQL statement.');
                window.location.href = 'dashboard.php';
              </script>";
    }
}

// Fetch inactive users (status = 0)
$sql = "SELECT id, username, role FROM users_tbl WHERE status = 0";
$result = $conn->query($sql);
?>
<br>
<br>
<br>
<br>
<div class="container">
    <div class="overview-boxes">
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Pickup Request</div>
                <div class="number"></div>
                <div class="indicator">
                    <span class="text">Pending</span>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Packages</div>
                <div class="number"></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt icon"></i>
                    <span class="text">Packages</span>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Deliveries</div>
                <div class="number"></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt icon"></i>
                    <span class="text">Total weight of packages</span>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Consignments</div>
                <div class="number"></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt icon"></i>
                    <span class="text">Total processed</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<style>
    /* General Container */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Overview Boxes */
.overview-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.box {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    flex: 1;
    min-width: 200px;
}

.right-side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.box-topic {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 10px;
}

.number {
    font-size: 2em;
    color: #333;
    margin-bottom: 10px;
}

.indicator {
    display: flex;
    align-items: center;
    gap: 10px;
}

.indicator .text {
    font-size: 0.9em;
    color: #666;
}

/* Icons */
.icon {
    font-size: 1.2em;
    color: #007bff; /* Example color, adjust as needed */
}

</style>