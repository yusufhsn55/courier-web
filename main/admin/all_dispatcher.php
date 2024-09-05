<?php
include('includes/header.php');

$sql = "
SELECT 
    dispatcher_id, 
    first_name, 
    last_name, 
    email, 
    phone_number, 
    address, 
    city, 
    postal_code, 
    country, 
    national_id, 
    vehicle_type, 
    vehicle_registration_number, 
    date_joined, 
    status, 
    emergency_contact_name, 
    emergency_contact_phone, 
    emergency_contact_relationship
FROM 
    courier_dispatchers
ORDER BY 
    dispatcher_id ASC";

$result = mysqli_query($conn, $sql);
?>


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
                                <th>No.</th>
                                <th>Name</th>
                              
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Postal Code</th>
                                <th>Country</th>
                                <th>National ID</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle Registration Number</th>
                                <th>Date Joined</th>
                                <th>Status</th>
                                <th>Emergency Contact Name</th>
                                <th>Emergency Contact Phone</th>
                                <th>Emergency Contact Relationship</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $row['first_name'] ." ". $row['last_name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone_number'] . "</td>";
                                    echo "<td>" . $row['address'] . "</td>";
                                    echo "<td>" . $row['city'] . "</td>";
                                    echo "<td>" . $row['postal_code'] . "</td>";
                                    echo "<td>" . $row['country'] . "</td>";
                                    echo "<td>" . $row['national_id'] . "</td>";
                                    echo "<td>" . $row['vehicle_type'] . "</td>";
                                    echo "<td>" . $row['vehicle_registration_number'] . "</td>";
                                    echo "<td>" . $row['date_joined'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['emergency_contact_name'] . "</td>";
                                    echo "<td>" . $row['emergency_contact_phone'] . "</td>";
                                    echo "<td>" . $row['emergency_contact_relationship'] . "</td>";
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
                                echo "<tr><td colspan='17'>No records found</td></tr>";
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
