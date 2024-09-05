<?php

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {

  function generateRandomString($length = 6) {
    $bytes = random_bytes($length);
    return substr(bin2hex($bytes), 0, $length);
}

// Get the user_id from the session
  $user_id = $_SESSION['user_id'];

// Table Increment Value 
 $i=1;


// Create a function to fetch the customer id
function getCustomerId($user_id, $conn)
{
  
  $user_id = mysqli_real_escape_string($conn, $user_id);
  $query = "SELECT id FROM customers WHERE user_id = $user_id";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    
    $row = mysqli_fetch_assoc($result);
    return $row['id'];
  } else {
    return null; 
  }
}


// Function to get the full name of the customer
function getCustomersFullName($user_id, $conn) {
   
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $query = "SELECT * FROM customers WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
        return $row['first_name'] . ' ' . $row['last_name'];
    } else {
        return null; 
    }
}

function generateRandomUsername($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomUsername = '';
    for ($i = 0; $i < $length; $i++) {
        $randomUsername .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomUsername;
}

function generateRandomPassword($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}


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