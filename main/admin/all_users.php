<?php
include('includes/header.php');

// Check if the connection is set
if (!isset($conn)) {
    die("Database connection not established.");
}




// Get the selected role from the dropdown, if any
$selected_role = isset($_GET['role']) ? $_GET['role'] : '';

// Adjust the SQL query based on the selected role
$sql = "
    SELECT 
        users_tbl.id AS user_id, 
        users_tbl.username, 
        users_tbl.role, 
        users_tbl.status,
        employees_tbl.first_name, 
        employees_tbl.last_name, 
        employees_tbl.email, 
        employees_tbl.phone, 
        users_tbl.created_at AS user_created_at, 
        employees_tbl.created_at AS employee_created_at
    FROM 
        users_tbl
    LEFT JOIN 
        employees_tbl 
    ON 
        users_tbl.id = employees_tbl.emply_id";

if ($selected_role != '') {
    $sql .= " WHERE users_tbl.role = '" . $conn->real_escape_string($selected_role) . "'";
}

$sql .= " ORDER BY users_tbl.id ASC";

$result = $conn->query($sql);
?>

<div class="main-box">
    <div class="section-box box">
        <div class="title-box">Account User Management: </div>
        <div class="box-details">
            <ul class="details">
                <li class="topic"> 
                    <div class="button">
                        <a href="add_users.php">Add Users</a>
                    </div>
                </li>
                <li class="topic">
                    <select id="roleFilter" onchange="filterByRole(this.value)">
                        <option value="">All Roles</option>
                        <option value="Courier Manager" <?php if ($selected_role == 'Courier Manager') echo 'selected'; ?>>Courier Manager</option>
                        <option value="Courier" <?php if ($selected_role == 'Courier') echo 'selected'; ?>>Courier Dispatcher</option>
                        <option value="customer" <?php if ($selected_role == 'customer') echo 'selected'; ?>>Customer</option>
                        <option value="Warehouse Staff" <?php if ($selected_role == 'Warehouse Staff') echo 'selected'; ?>>Warehouse Staff</option>
                    </select>
                </li>
            </ul>
            <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>User Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($result->num_rows > 0) {
        $i = 1;
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . ($row['status'] > 0 ? "Active" : "Inactive") . "</td>";
            echo "<td>" . $row['user_created_at'] . "</td>";
            echo "<td>
                    <div class='multi-button'>
                        <form method='get' action=''>
                            <input type='hidden' name='delete_user_id' value='" . $row['user_id'] . "'>
                            <button type='submit' class='btn-tbl' id='delete'><span><div class='icon-img'>
                            <img src='assets/img/icons/trash.png' alt='Delete' />
                          </div></span></button>
                        </form>
                        <button class='btn-tbl' id='edit'><a href='edit_user.php?user_id=" . $row['user_id'] . "'><span> <div class='icon-img'>
                        <img src='assets/img/icons/edit.png' alt='Edit' />
                      </div></span></a></button>
                        <button class='btn-tbl' id='cut'><span>Cut</span></button>
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
        </div>
    </div>
</div>

<script>
function filterByRole(role) {
    window.location.href = "?role=" + role;
}
</script>

<?php
include('includes/footer.php');
?>
