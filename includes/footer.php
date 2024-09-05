<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <style>
        /* Add some basic styling for the map container */
        #map {
            height: 400px; /* Set the height of the map */
            width: 100%;   /* Set the width of the map to 100% */
            margin-bottom: 20px; /* Add some space below the map */
        }
        iframe {
            width: 100%; /* Ensure iframe takes full width of the container */
            height: 100%; /* Ensure iframe height is 100% of the container */
            border: 0; /* Remove default iframe border */
        }
    </style>
</head>
<body>
    <!-- Your page content here -->

    <!-- Map container -->
    <div id="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15800.421670194394!2d36.805536803243166!3d-1.2863890291540584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f168ab5ebd7d5%3A0x70b6e51b9cb8b6ef!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2sus!4v1694617695437!5m2!1sen!2sus"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <footer>
        <div class="footer-bottom">
            &copy; 2024 kushiticcourier.com | yussuf | support admin@kushiticcourier.com
        </div>
    </footer>
    <script> 
        function toggleMenu() {
            var navLinks = document.getElementById("navLinks");
            if (navLinks.style.display === "block") {
                navLinks.style.display = "none";
            } else {
                navLinks.style.display = "block";
            }
        }
    </script>
    <script src="assets/js/script.js"></script>

</body>
</html>
