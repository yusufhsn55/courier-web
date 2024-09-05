<?php

    include('includes/header.php');
    
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
        users_tbl.id = employees_tbl.emply_id
    ORDER BY 
        users_tbl.id ASC";

    
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
                <div class="box-topic">Assign Deliveries
</div>
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
          <div class=" section-box box">
            
            <div class="title-box">List of System Users</div>
            <div class="box-details">
              
              <ul class="details">
                <li class="topic"> 
                  <div class="button">
                    <a href="add_users.php">Add Users</a>
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
                        <button class='btn-tbl' id='cut'><span><div class='icon-img'>
                        <img src='assets/img/icons/trash.png' alt='' />
                      </div></span></button>
                        <button class='btn-tbl' id='edit'><span> <div class='icon-img'>
                        <img src='assets/img/icons/edit.png' alt='' />
                      </div></span></button>
                        <button class='btn-tbl' id='cut'><span> Cut</span></button>
                        
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
        <?php

include('includes/footer.php');

?>
 