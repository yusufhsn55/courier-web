<?php
include('includes/header.php');

// Fetch data from the consignments table
$sql = "
SELECT 
    consignments.shippername,
    consignments.shipperaddress,
    consignments.phone,
    consignments.material_description,
    consignments.no_of_item,
    consignments.branch_id,
    consignments.booked_at_branch,
    consignments.branchlocation,
    consignments.date_of_booking,
    consignments.destination,
    consignments.shipment_charge,
    consignments.total_weight,
    consignments.corporate_id,
    consignments.receiver_name,
    consignments.receiver_address,
    consignments.receiver_phone,
    consignments.ccn,
    consignments.booking_id
FROM 
    consignments
ORDER BY 
    consignments.date_of_booking DESC";

$result = $conn->query($sql);
?>

<div class="overview-boxes">
    <button class="box" onclick="window.print()">
        <div class="right-side">
            <div class="box-topic">Print Report</div>
        </div>   
    </button>
</div>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">consignments Report</div>
        <div class="box-details">
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Shipper Name</th>
                            <th>Shipper Address</th>
                            <th>Phone</th>
                            <th>Material Description</th>
                            <th>No of Items</th>
                            <th>Branch ID</th>
                            <th>Booked At Branch</th>
                            <th>Branch Location</th>
                            <th>Date of Booking</th>
                            <th>Destination</th>
                            <th>Shipment Charge</th>
                            <th>Total Weight</th>
                            <th>Corporate ID</th>
                            <th>Receiver Name</th>
                            <th>Receiver Address</th>
                            <th>Receiver Phone</th Announced>
                            <th>CCN</th>
                            <th>Booking ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['shippername'] . "</td>";
                                echo "<td>" . $row['shipperaddress'] . "</td>";
                                echo "<td>" . $row['phone'] . "</td>";
                                echo "<td>" . $row['material_description'] . "</td>";
                                echo "<td>" . $row['no_of_item'] . "</td>";
                                echo "<td>" . $row['branch_id'] . "</td>";
                                echo "<td>" . $row['booked_at_branch'] . "</td>";
                                echo "<td>" . $row['branchlocation'] . "</td>";
                                echo "<td>" . $row['date_of_booking'] . "</td>";
                                echo "<td>" . $row['destination'] . "</td>";
                                echo "<td>" . $row['shipment_charge'] . "</td>";
                                echo "<td>" . $row['total_weight'] . "</td>";
                                echo "<td>" . $row['corporate_id'] . "</td>";
                                echo "<td>" . $row['receiver_name'] . "</td>";
                                echo "<td>" . $row['receiver_address'] . "</td>";
                                echo "<td>" . $row['receiver_phone'] . "</td>";
                                echo "<td>" . $row['ccn'] . "</td>";
                                echo "<td>" . $row['booking_id'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='19'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    @media print {
        .overview-boxes, .button, header, footer {
            display: none;
        }

        .main-box {
            margin: 0;
            padding: 0;
        }

        .table-wrapper {
            width: 100%;
            border-collapse: collapse;
        }

        .table-wrapper th, .table-wrapper td {
            border: 1px solid #000;
            padding: 8px;
        }
    }
</style>
