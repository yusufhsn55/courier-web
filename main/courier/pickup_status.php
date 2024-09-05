<?php
include('includes/header.php');

$sql = "SELECT * FROM consignments WHERE dispatcher_pickup_status = 'pending' ORDER BY consignment_id ASC";

$result = $conn->query($sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">Pending Pickup</div>
        <div class="box-details">
            <ul class="details">
                
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $trackId = htmlspecialchars($row['tracking_number']);
                                  
                                    echo "<tr>";
                                    echo "<td>" . $i++. "</td>";
                                    echo "<td>" . $row['tracking_number'] . "</td>";
                                    echo "<td>" . $row['dispatcher_pickup_status'] . "</td>";
                                    echo "<td>" . $row['delivery_status'] . "</td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "<td>" . $row['updated_at'] . "</td>";  
                                    echo "<td>
                                    <div class='multi-button'>
                                      
                                        <form action='view_consignment_status.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='tracking_number' value='$trackId' />
                                            <button type='submit' class='btn-tbl'>
                                                <span><div class='icon-img'>
                                                    <img src='assets/img/icons/delivery (1).png' alt='Status' />
                                                </div></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>";
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
