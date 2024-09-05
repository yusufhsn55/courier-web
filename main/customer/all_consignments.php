<?php
include('includes/header.php');

$senderID = getCustomerId($user_id, $conn);
$sql = "SELECT * FROM consignments WHERE sender_id = '$senderID' ORDER BY consignment_id ASC";

$result = $conn->query($sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">All Deliveries</div>
        <div class="box-details">
            <ul class="details">
                <li class="topic">
                    
                </li>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tracking Number</th>
                                <th>Dispatcher Pickup Status</th>
                                <th>Delivery Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $trackId = htmlspecialchars($row['tracking_number']);
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $row['tracking_number'] . "</td>";
                                    echo "<td>" . $row['dispatcher_pickup_status'] . "</td>";
                                    echo "<td>" . $row['delivery_status'] . "</td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "<td>" . $row['updated_at'] . "</td>";  
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='13'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </ul>
        </div>
    </div>
</div>

<?php
$conn->close();
include('includes/footer.php'); 
?>
