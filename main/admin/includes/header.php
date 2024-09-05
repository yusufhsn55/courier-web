<?php
session_start();
require ('config.php');
require ('includes/functions.php');

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css" />

    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon_icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon_icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon_icons/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

  </head>
  <body>
    <div class="sidebar">
      <div class="logo-details">
      <div class=" icon-img">
              <img src="assets/img/icons/worldwide-delivery.png" alt="" />
      </div>
        <span class="logo_name">KUSHITIC</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="index.php" class="active">
            <div class=" icon-img">
              <img src="assets/img/icons/dashboards.png" alt="" />
            </div>

            <span class="links_name">Dashboard</span>
          </a>
        </li>
        
        <li class="dropdown">
            <a href="#">
                <div class="icon-img">
                    <img src="assets/img/icons/worker.png" alt="" />
                </div>
                <span class="links_name">User Management</span>
                <i class="uil uil-angle-down dropdown-icon"></i> 
            </a>
            <ul class="dropdown-menu">
                <li><a href="all_customers.php">Client Users </a></li>
                <li><a href="all_dispatcher.php">Dispatchers</a></li>
            </ul>
        </li>
        <li>
          <a href="all_customers.php">
          <div class=" icon-img">
              <img src="assets/img/icons/worker.png" alt="" />
            </div>
            <span class="links_name">Customer Management</span>
          </a>
        </li>
        
        <li class="dropdown">
            <a href="#">
                <div class="icon-img">
                    <img src="assets/img/icons/trolley.png" alt="" />
                </div>
                <span class="links_name">Package Management</span>
                <i class="uil uil-angle-down dropdown-icon"></i> 
            </a>
            <ul class="dropdown-menu">
                <li><a href="add_package.php">Add Package </a></li>
                <li><a href="all_packages.php">Track All Packages</a></li>
                <li><a href="add_package.php">Update Package Status</a></li>
                <li><a href="package_reports.php">Package Reports</a></li>
            </ul>
        </li>
        
        <li>
          <a href="all_dispatcher.php">
          <div class=" icon-img">
              <img src="assets/img/icons/worker.png" alt="" />
            </div>
            <span class="links_name">Dispatcher Management</span>
          </a>
        </li>
        
        <li>
          <a href="all_consignment.php">
            <div class=" icon-img">
              <img src="assets/img/icons/delivery-courier.png" alt="" />
            </div>
            <span class="links_name">Deliveries</span>
          </a>
        </li>
        


        <li>
          <a href="reports.php">
          <div class=" icon-img">
              <img src="assets/img/icons/delivery-courier.png" alt="" />
          </div>
            <span class="links_name">Reports</span>
          </a>
        </li>
       
        <li class="log_out">
          <a href="logout.php">
          <div class=" icon-img">
              <img src="assets/img/icons/switch.png" alt="" />
            </div>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
    </div>
    
<section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class="bx bx-menu sidebarBtn"></i>
          <span class="dashboard">Dashboard</span>
        </div>
        <div class="profile-details">
          
          <img src="assets/img/icons/profile.png" alt="" />
          <span class="admin_name">Admin</span>
          
        </div>
      
          
       

      </nav>
      <div class="home-content">
      <script>
      document.addEventListener("DOMContentLoaded", function() {
        const dropdowns = document.querySelectorAll(".nav-links li.dropdown");

        dropdowns.forEach(function(dropdown) {
          const menu = dropdown.querySelector(".dropdown-menu");
          const icon = dropdown.querySelector(".dropdown-icon");

          dropdown.addEventListener("click", function() {
            // Close other open dropdowns
            dropdowns.forEach(d => {
              if (d !== dropdown) {
                d.classList.remove("active");
                d.querySelector(".dropdown-menu").style.display = "none";
              }
            });

            // Toggle this dropdown
            dropdown.classList.toggle("active");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
          });

          // Close dropdown when clicking outside
          document.addEventListener("click", function(event) {
            if (!dropdown.contains(event.target)) {
              dropdown.classList.remove("active");
              menu.style.display = "none";
            }
          });
        });
      });
      </script>
