 </div>
    </section>

    <script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      };
    </script>

<script>
    document.getElementById('profile-btn').addEventListener('change', function() {
        var selectedValue = this.value;
        if (selectedValue) {
            window.location.href = selectedValue;
        }
    });
</script>

    <script src="assets/js/script.js"></script>
  </body>
</html>
