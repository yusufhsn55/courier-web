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
        c.receiver_id = '$customerId'
    AND 
        c.delivery_status = 'delivered'
    ORDER BY 
        p.id ASC";

$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <div class="card profile-card">
        <h2>List of Received Packages</h2>
        <p>Customer: <strong><?php echo htmlspecialchars(getCustomersFullName($user_id, $conn)); ?></strong></p>
        <div class="row">
            <div class="col">
                <a href="add_package.php" class="btn-add-package">Add Package</a>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>Tracking No.</th>
                        <th>Package</th>
                        <th>Billing Status</th>
                        <th>Package Created At</th>
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
                            $trackingNumber = htmlspecialchars($row['tracking_number']);
                            $dispatcherId = htmlspecialchars($row['dispatcher_id']);
                            $dispatcherName = getDispatcherInfo($dispatcherId, $conn);

                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . $trackingNumber . "</td>";
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
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #333;
    }

    p {
        font-size: 16px;
        margin-bottom: 20px;
        color: #555;
    }

    .btn-add-package {
        background-color: #28a745;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-add-package:hover {
        background-color: #218838;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .fl-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fl-table th,
    .fl-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .fl-table thead th {
        background-color: #f2f2f2;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .fl-table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
