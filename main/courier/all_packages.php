<?php
include('includes/header.php');

// Ensure $user_id and $conn are properly defined
$customerId = getCustomerId($user_id, $conn);
$sql = "SELECT * FROM packages WHERE customer_id = '$customerId' ORDER BY id ASC";

// Execute the query
$result = mysqli_query($conn, $sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">List of Packages</div>
        <div class="box-details">
            <div class="button">
                <a href="add_package.php">Add Package</a>
            </div>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>Package ID</th>
                            <th>Customer ID</th>
                            <th>Description</th>
                            <th>Weight (kg)</th>
                            <th>Dimensions</th>
                            <th>Billing Status</th>
                            <th>Shipment Status</th>
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
                                echo "<tr>";
                                echo "<td>$packageId</td>";
                                echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['weight']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['dimensions']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['value']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                                echo "<td>
                                    <div class='multi-button'>
                                        <button class='btn-tbl edit-btn'><span><div class='icon-img'>
                                        <img src='assets/img/icons/edit.png' alt='Edit' />
                                      </div></span></button>
                                        <button class='btn-tbl delete-btn'><span><div class='icon-img'>
                                        <img src='assets/img/icons/trash.png' alt='Delete' />
                                      </div></span></button>
                                        <form action='generate_consignment.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='package_id' value='$packageId' />
                                            <button type='submit' class='btn-tbl generate-consignment-btn'>
                                                <span><div class='icon-img'>
                                                    <img src='assets/img/icons/add.png' alt='Generate Consignment' />
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
include('includes/footer.php');
?>
