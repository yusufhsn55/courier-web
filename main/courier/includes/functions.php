<?php


if (isset($_SESSION['user_id'])) {

  $user_id = $_SESSION['user_id'];

  function generateRandomString($length = 6) {
    $bytes = random_bytes($length);
    return substr(bin2hex($bytes), 0, $length);
}



// Increment $i
$i = 1;


function getCustomersFullName($user_id, $conn) {
    
    $user_id = mysqli_real_escape_string($conn, $user_id);
   
    $query = "SELECT * FROM courier_dispatchers WHERE dispatcher_id = $user_id";
    
    $result = mysqli_query($conn, $query);
    
    
    if ($result && mysqli_num_rows($result) > 0) {
        
        $row = mysqli_fetch_assoc($result);
        
        return $row['first_name'] . ' ' . $row['last_name'];
    } else {
        return null; 
    }
}


function getTotalPendingDispatches($conn) {
  
  $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS total FROM consignments WHERE dispatcher_id = '$user_id' AND delivery_status = 'created' ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
  }
  $totalPending = getTotalPendingDispatches($conn);

  function getTotalDeliveredDispatches($conn) {
  
    $user_id = $_SESSION['user_id'];
      $sql = "SELECT COUNT(*) AS total FROM consignments WHERE dispatcher_id = '$user_id' AND delivery_status = 'delivered' ";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      return $row['total'];
    }
    $totalDelivered = getTotalDeliveredDispatches($conn);
  

function getDispatcherInfo($dispatcher_id, $conn) {
  $sql = "SELECT * FROM courier_dispatchers WHERE dispatcher_id = '$dispatcher_id'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
      return mysqli_fetch_assoc($result);
  }
  return null;
}


function getSenderInfo($sender_id, $conn) {
  $sql = "SELECT * FROM customers WHERE id = '$sender_id'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
      return mysqli_fetch_assoc($result);
  }
  return null;
}

function getReceiverInfo($receiver_id, $conn) {
  $sql = "SELECT * FROM customers WHERE id = '$receiver_id'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
      return mysqli_fetch_assoc($result);
  }
  return null;
}

function getPackageInfo($package_id, $conn) {
  $sql = "SELECT * FROM packages WHERE id = '$package_id'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
      return mysqli_fetch_assoc($result);
  }
  return null;
}




} else {
  echo '<script>alert("No account found logged in.");</script>';
  header("Location: ../../");
}