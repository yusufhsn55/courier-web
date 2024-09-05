<?php
include('includes/header.php');

$dispatcher = $_SESSION['user_id'];

$sql = "SELECT * FROM consignments WHERE dispatcher_id = '$dispatcher' AND delivery_status = 'in_transit'";
$result = mysqli_query($conn, $sql);
$dispatcher_info = getDispatcherInfo($dispatcher, $conn);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box"> Accepted Pickups (In-transit)</div>
        <div class="box-details">
            <ul class="details">
                
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>N.o</th>
                                <th>Tracking Number</th>
                                <th>Dispatcher</th>
                                <th>Dispatcher Pickup Status</th>
                                <th>Delivery Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && mysqli_num_rows($result) > 0) {
                                $i = 1;
                                while($row = mysqli_fetch_assoc($result)) {
                                    $trackId = htmlspecialchars($row['tracking_number']);
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tracking_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($dispatcher_info['first_name']."".$dispatcher_info['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['dispatcher_pickup_status']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['delivery_status']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                                    echo "<td>
                                            <div class='multi-button'>
                                                <form action='view_consignment_status.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='tracking_number' value='$trackId' />
                                                    <button type='submit' class='btn-tbl'>
                                                        <span>
                                                            <div class='icon-img'>
                                                                <img src='assets/img/icons/delivery (1).png' alt='Status' />
                                                            </div>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No records found</td></tr>";
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
mysqli_close($conn);
include('includes/footer.php'); 
?>
