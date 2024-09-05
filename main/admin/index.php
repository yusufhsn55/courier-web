<?php


include('includes/header.php');


if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $userId = (int)$_GET['user_id'];
    
    // Prepare SQL query based on action
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
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating user status.');
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

$sql = "SELECT id, username, role FROM users_tbl WHERE status = 0";
$result = $conn->query($sql);
?>

<div class="overview-boxes">
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Users</div>
                <div class="number"><?php echo $totalUsers; ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Users registered</span>
                </div>
            </div>
            
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Packages</div>
                <div class="number"><?php echo $totalPackages; ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Packages processed</span>
                </div>
            </div>
          
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Weight</div>
                <div class="number"><?php echo $totalWeight; ?> kg</div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Total weight of packages</span>
                </div>
            </div>
            
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Value</div>
                <div class="number"><?php echo $totalValue; ?> KSH</div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Total value of packages</span>
                </div>
            </div>
            
        </div>
    </div>



<?php include('includes/footer.php'); ?>


 