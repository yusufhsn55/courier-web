<?php
include('includes/header.php');

$customerId = getCustomerId($user_id, $conn);

$sql = "
    SELECT 
        p.id AS package_id,
        p.product_name,
        p.weight,
        p.dimensions,
        p.value,
        p.created_at AS package_created_at,
        p.updated_at AS package_updated_at,
        c.consignment_id,
        c.package_id,
        c.tracking_number,
        c.dispatcher_id,
        c.dispatcher_pickup_status,
        c.delivery_status,
        c.created_at AS consignment_created_at,
        c.updated_at AS consignment_updated_at
    FROM 
        packages p
    LEFT JOIN 
        consignments c ON p.id = c.package_id
    WHERE 
        p.customer_id = '$customerId'
    AND 
        c.delivery_status = 'delivered'
    ORDER BY 
        p.id ASC";

$result = mysqli_query($conn, $sql);
?>

<div class="main-container">
    <div class="section-box box">
        <header class="title-box">
            List of Sent Packages for <?php echo getCustomersFullName($user_id, $conn); ?>
        </header>
        <div class="actions">
            <a href="add_package.php" class="btn add-package-btn">Add Package</a>
        </div>
        <div class="table-wrapper">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tracking No.</th>
                        <th>Package</th>
                        <th>Value</th>
                        <th>Created At</th>
                        <th>Dispatcher</th>
                        <th>Pickup Status</th>
                        <th>Delivery Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $packageId = htmlspecialchars($row['package_id']);
                            $consignmentId = htmlspecialchars($row['consignment_id']);
                            $trackingNumber = htmlspecialchars($row['tracking_number']);
                            $dispatcherId = htmlspecialchars($row['dispatcher_id']);
                            $dispatcherName = getDispatcherInfo($dispatcherId, $conn);
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($trackingNumber) . "</td>";
                            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['value']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['package_created_at']) . "</td>";
                            echo "<td>" . htmlspecialchars($dispatcherName['first_name'] . " " . $dispatcherName['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dispatcher_pickup_status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['delivery_status']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$conn->close();
include('includes/footer.php');
?>

<style>
.main-container {
    padding: 20px;
}

.title-box {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.actions {
    margin-bottom: 20px;
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
}

.btn:hover {
    background-color: #0056b3;
}

.table-wrapper {
    overflow-x: auto;
}

.responsive-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    font-size: 16px;
    text-align: left;
}

.responsive-table thead {
    background-color: #f8f9fa;
}

.responsive-table th,
.responsive-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
}

.responsive-table tr:nth-of-type(even) {
    background-color: #f2f2f2;
}
</style>
