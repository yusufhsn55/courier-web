<?php
include('includes/header.php');

$sql = "
SELECT 
    id, 
    ccn, 
    Branch_name, 
    shipper_name, 
    phone, 
    sender_address, 
    receiver_name, 
    receiver_phoneno, 
    receiver_address, 
    assignto, 
    dispatcher_Id, 
    dispatchid
FROM 
    dispatcher
ORDER BY 
    id ASC";

$result = $conn->query($sql);
?>

<div class="overview-boxes">
    <button class="box">
        <div class="right-side">
            <div class="box-topic">Manage Couriers</div>
        </div>   
    </button>
    <button class="box">
        <div class="right-side">
            <div class="box-topic">Assign Deliveries</div>
        </div>   
    </button>
    <button class="box">
        <div class="right-side">
            <div class="box-topic">Total Users</div>
        </div>   
    </button>
    <button class="box">
        <div class="right-side">
            <div class="box-topic">Total Users</div>
        </div>   
    </button>
</div>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">List of Dispatchers</div>
        <div class="box-details">
            <ul class="details">
                <li class="topic"> 
                    <div class="button">
                        <a href="add_dispatcher.php">Add Dispatcher</a>
                    </div>
                </li>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CCN</th>
                                <th>Branch Name</th>
                                <th>Shipper Name</th>
                                <th>Phone</th>
                                <th>Sender Address</th>
                                <th>Receiver Name</th>
                                <th>Receiver Phone No</th>
                                <th>Receiver Address</th>
                                <th>Assigned To</th>
                                <th>Dispatcher ID</th>
                                <th>Dispatch ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['ccn'] . "</td>";
                                    echo "<td>" . $row['Branch_name'] . "</td>";
                                    echo "<td>" . $row['shipper_name'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . $row['sender_address'] . "</td>";
                                    echo "<td>" . $row['receiver_name'] . "</td>";
                                    echo "<td>" . $row['receiver_phoneno'] . "</td>";
                                    echo "<td>" . $row['receiver_address'] . "</td>";
                                    echo "<td>" . $row['assignto'] . "</td>";
                                    echo "<td>" . $row['dispatcher_Id'] . "</td>";
                                    echo "<td>" . $row['dispatchid'] . "</td>";
                                    echo "<td>
                                            <div class='multi-button'>
                                                <button class='btn-tbl' id='cut'>
                                                    <span>
                                                        <div class='icon-img'>
                                                            <img src='assets/img/icons/trash.png' alt='' />
                                                        </div>
                                                    </span>
                                                </button>
                                                <button class='btn-tbl' id='edit'>
                                                    <span>
                                                        <div class='icon-img'>
                                                            <img src='assets/img/icons/edit.png' alt='' />
                                                        </div>
                                                    </span>
                                                </button>
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

<?php include('includes/footer.php'); ?>
