<?php
include('includes/header.php');

if (isset($_GET['delete_user_id'])) {
    $delete_user_id = intval($_GET['delete_user_id']);

    
    mysqli_begin_transaction($conn);

    try {
        
        $deleteCustomerSql = "DELETE FROM customers WHERE user_id = $delete_user_id";
        $deleteCustomerResult = mysqli_query($conn, $deleteCustomerSql);

      
        $deleteUserSql = "DELETE FROM users_tbl WHERE id = $delete_user_id";
        $deleteUserResult = mysqli_query($conn, $deleteUserSql);

        
        if ($deleteCustomerResult && $deleteUserResult) {
            
            mysqli_commit($conn);
            echo "<script>
                    alert('User deleted successfully.');
                    window.location.href = 'all_customers.php';
                  </script>";
        } else {
            throw new Exception("Error in deleting user.");
        }
    } catch (Exception $e) {
        
        mysqli_rollback($conn);
        echo "<script>
                alert('Error deleting user: " . $e->getMessage() . "');
                window.location.href = 'all_customers.php';
              </script>";
    }
}

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
INNER JOIN 
    customers 
ON 
    users_tbl.id = customers.user_id
WHERE 
    users_tbl.role = 'customer'
ORDER BY 
    users_tbl.id ASC";

$result = mysqli_query($conn, $sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">All Registered Clients</div>
        <div class="box-details">
            <ul class="details">
                <li class="topic"> 
                    <div class="button">
                        <a href="add_customer.php">Add New</a>
                    </div>
                </li>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>No.</th>
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
                            if (mysqli_num_rows($result) > 0) {
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $client_id = $row['user_id'];
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $row['first_name'] . "</td>";
                                    echo "<td>" . $row['last_name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . $row['user_created_at'] . "</td>";
                                    echo "<td>
                                            <div class='multi-button'>
                                                <form method='get' action=''>
                                                    <input type='hidden' name='delete_user_id' value='" . $row['user_id'] . "'>
                                                    <button type='submit' class='btn-tbl' id='delete'>
                                                        <span><div class='icon-img'>
                                                        <img src='assets/img/icons/trash.png' alt='Delete' /></div></span>
                                                    </button>
                                                </form>
                                                <form action='edit_customer.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='customer_id' value='" . $client_id . "' />
                                                    <button type='submit' class='btn-tbl generate-consignment-btn'>
                                                        <span><div class='icon-img'>
                                                        <img src='assets/img/icons/profile.png'  /></div></span>
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
