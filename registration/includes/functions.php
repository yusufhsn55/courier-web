<?php

// Function to calculate the total number of users
function getTotalUsers($conn) {
  $sql = "SELECT COUNT(*) AS total FROM users_tbl";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

// Function to calculate the total number of packages
function getTotalPackages($conn) {
  $sql = "SELECT COUNT(*) AS total FROM packages";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

// Function to calculate the total weight of packages
function getTotalWeight($conn) {
  $sql = "SELECT SUM(weight) AS total FROM packages";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

// Function to calculate the total value of packages
function getTotalValue($conn) {
  $sql = "SELECT SUM(value) AS total FROM packages";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

$totalUsers = getTotalUsers($conn);
$totalPackages = getTotalPackages($conn);
$totalWeight = getTotalWeight($conn);
$totalValue = getTotalValue($conn);


?>

