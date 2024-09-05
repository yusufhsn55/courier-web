<?php
include('includes/header.php');


$sql = "SELECT * FROM packages ORDER BY id ASC";

// Execute the query
$result = mysqli_query($conn, $sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">Listed Packages</div>
        <div class="box-details">
            
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Package</th>
                            <th>Weight (kg)</th>
                            <th>Dimensions</th>
                            <th>Billing Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                           
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        
        while($row = mysqli_fetch_assoc($result)) {
            $packageId = htmlspecialchars($row['id']);
            $customerId = htmlspecialchars($row['customer_id']);
            echo "<tr>";
            echo "<td>". $i++ ."</td>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['weight']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dimensions']) . "</td>";
            echo "<td>" . htmlspecialchars($row['value']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No records found</td></tr>";
    }
    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_delete'])) {
    $packageId = mysqli_real_escape_string($conn, $_POST['package_delete']);
    
    $query = "DELETE FROM packages WHERE id = '$packageId'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "<script>
        alert('Package Deleted successfully!');
        window.location.href = 'all_packages.php';
      </script>";
    } else {
        echo "<script>
        alert('Package Error Deleted');
        window.location.href = 'all_packages.php';
      </script>";
    }
    exit();
}
include('includes/footer.php');
?>
