<?php

$conn = new mysqli("localhost", "root", "", "kushitic");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
