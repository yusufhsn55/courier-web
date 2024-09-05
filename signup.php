<?php
    include('includes/header.php');
 

?>
        <section class="container forms">
        <div class="form signup">
        <div class="form-content">
            <header>Signup</header>
            <form action="" method="POST">
                <div class="field input-field">
                    <input type="text" name="full_name" placeholder="Enter your name" class="input" required>
                </div>
                <div class="field input-field">
                    <input type="email" name="email" placeholder="Enter your email" class="input" required>
                </div>
                <div class="field input-field">
                    <input type="text" name="phone" placeholder="Enter mobile number" class="input" required>
                </div>
                <div class="field input-field">
                    <select name="occupation" class="input" required>
                        <option disabled selected>Select user role</option>
                        <option value="Admin">Admin</option>
                        <option value="Courier Manager">Courier Manager</option>
                        <option value="Courier Dispatcher">Courier Dispatcher</option>
                        <option value="Customer Service Representative">Customer Service Representative</option>
                        <option value="Warehouse Staff">Warehouse Staff</option>
                    </select>
                </div>
                <div class="field input-field">
                    <input type="text" name="username" placeholder="Enter username" class="input" required>
                </div>
                <div class="field input-field">
                    <input type="password" name="password" placeholder="Enter password" class="password" required>
                </div>
                <div class="field button-field">
                    <button type="submit">Register</button>
                </div>
            </form>
            <div class="form-link">
                <span>Already have an account? <a href="#" class="link login-link">Login</a></span>
            </div>
        </div>
        <div class="line"></div>
        <div class="media-options">
            <a href="#" class="field facebook">
                <i class='bx bxl-facebook facebook-icon'></i>
                <span>Login with Facebook</span>
            </a>
        </div>
        <div class="media-options">
            <a href="#" class="field google">
                <img src="assets/images/google.png" alt="Google" class="google-img">
                <span>Login with Google</span>
            </a>
        </div>
    </div>
            <!-- Signup Form -->

            <div class="form signup">
                <div class="form-content">
                    <header>Signup</header>
                    <form action="#">
                        <div class="field input-field">
                            <input type="email" placeholder="Email" class="input">
                        </div>

                        <div class="field input-field">
                            <input type="password" placeholder="Create password" class="password">
                        </div>

                        <div class="field input-field">
                            <input type="password" placeholder="Confirm password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>

                        <div class="field button-field">
                            <button>Signup</button>
                        </div>
                    </form>

                    <div class="form-link">
                        <span>Already have an account? <a href="#" class="link login-link">Login</a></span>
                    </div>
                </div>

                <div class="line"></div>

                <div class="media-options">
                    <a href="#" class="field facebook">
                        <i class='bx bxl-facebook facebook-icon'></i>
                        <span>Login with Facebook</span>
                    </a>
                </div>

                <div class="media-options">
                    <a href="#" class="field google">
                        <img src="" alt="" class="google-img">
                        <span>Login with Google</span>
                    </a>
                </div>

            </div>
        </section>
 <?php

    include('includes/footer.php');

?>