<?php
include('includes/header.php');

$sql = "
SELECT 
    users_tbl.id AS user_id, 
    users_tbl.username, 
    users_tbl.role, 
    users_tbl.status,
    customers.first_name, 
    customers.last_name, 
    customers.email, 
    customers.phone, 
    users_tbl.created_at AS user_created_at
FROM 
    users_tbl
LEFT JOIN 
    customers 
ON 
    users_tbl.id = customers.user_id
ORDER BY 
    users_tbl.id ASC";

$result = $conn->query($sql);
?>


<div class="main-box">
    <div class="section-box box">
        <div class="title-box">List of System Users</div>
        <div class="box-details">
            <ul class="details">
                <li class="topic"> 
                    <div class="button">
                        <a href="add_customer.php">Add Users</a>
                    </div>
                </li>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>User Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['role'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['first_name'] . "</td>";
                                    echo "<td>" . $row['last_name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . $row['user_created_at'] . "</td>";
                                    echo "<td>
                                            <div class='multi-button'>
                                                <button class='btn-tbl' id='cut'>
                                                    <span>
                                                        <div class='icon-img'>
                                                            <img src='assets/img/icons/trash.png' alt='' />
                                                        </div>
                                                    </span>
                                                </button>
                                                <form action='edit_customer.php' method='post' style='display:inline;'>
                                                <input type='hidden' name='customer_id' value='<?php echo $client_id; ?>' />
                                                <button type='submit' class='btn-tbl generate-consignment-btn'>
                                                    <span>
                                                        <div class='icon-img'>
                                                            <img src='assets/img/icons/delivery (1).png' alt='Generate Consignment' />
                                                        </div>
                                                    </span>
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
            </ul>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
