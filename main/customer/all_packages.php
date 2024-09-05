<?php
include('includes/header.php');

$customerId = getCustomerId($user_id, $conn);
$sql = "SELECT * FROM packages WHERE customer_id = '$customerId' ORDER BY id ASC";

// Execute the query
$result = mysqli_query($conn, $sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">Listed Packages: <?php echo getCustomersFullName($user_id, $conn);?></div>
        <div class="box-details">
            <div class="button">
                <a href="add_package.php">Add Package</a>
            </div>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $packageId = htmlspecialchars($row['id']);
                                $customerId = htmlspecialchars($row['customer_id']);
                                echo "<tr>";
                                echo "<td>". $1++ ."</td>";
                                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['weight']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['dimensions']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['value']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                                echo "<td>
                                    <div class='multi-button'>
                                        <form action='all_packages.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='package_delete' value='$packageId' />
                                            <button type='submit' class='btn-tbl delete-btn'>
                                                <span><div class='icon-img'>
                                                    <img src='assets/img/icons/trash.png' alt='Delete' style='width: 16px; height: 16px;' />
                                                </div></span>
                                            </button>
                                        </form>
                                        <form action='handle_consignment_request.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='package_id' value='$packageId' />
                                            <button type='submit' class='btn-tbl generate-consignment-btn'>
                                                <span><div class='icon-img'>
                                                    <img src='assets/img/icons/delivery (1).png' alt='Generate Consignment' style='width: 16px; height: 16px;' />
                                                </div></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>";
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
                alert('Error deleting package.');
                window.location.href = 'all_packages.php';
              </script>";
    }
    exit();
}
include('includes/footer.php');
?>

<style>
    .main-box {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .section-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .title-box {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #007bff;
    }

    .box-details {
        margin-top: 20px;
    }

    .button .btn-add {
        background-color: #28a745;
        color: #fff;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
        transition: background-color 0.3s ease;
    }

    .button .btn-add:hover {
        background-color: #218838;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .fl-table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
    }

    .fl-table th,
    .fl-table td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .fl-table th {
        background-color: #007bff;
        color: white;
    }

    .fl-table tr:hover {
        background-color: #f1f1f1;
    }

    .multi-button {
        display: flex;
        gap: 10px;
    }

    .btn-tbl {
        border: none;
        background-color: transparent;
        cursor: pointer;
    }

    .icon-img {
        width: 24px;
        height: 24px;
    }

    .no-records {
        text-align: center;
        font-weight: bold;
        color: #555;
    }
</style>
