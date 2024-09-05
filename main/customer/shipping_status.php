<?php
include('includes/header.php');

$senderID = getCustomerId($user_id, $conn);

$sqlPending = "SELECT * FROM consignments WHERE sender_id = '$senderID' AND delivery_status = 'created' ORDER BY consignment_id ASC";
$resultPending = $conn->query($sqlPending);
?>

<div class="container">
    <div class="card">
        <h2>All Dispatch Status</h2>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tracking Number</th>
                        <th>Dispatcher Pickup Status</th>
                        <th>Delivery Status</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($resultPending->num_rows > 0) {
                        while($row = $resultPending->fetch_assoc()) {
                            $trackId = htmlspecialchars($row['tracking_number']);
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['tracking_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dispatcher_pickup_status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['delivery_status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                            echo "<td>
                                <a href='view_consignment_status.php?tracking_number=$trackId' class='btn-tbl'>View
                                </a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
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
        margin-bottom: 20px;
        color: #333;
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
        vertical-align: middle;
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

    .icon {
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }

    .btn-tbl {
        display: inline-block;
        text-decoration: none;
        color: #333;
    }
</style>
