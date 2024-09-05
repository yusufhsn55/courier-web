<?php

    include('includes/header.php');
 

?>
        <section class="container forms">
            <div class="form login">
                <div class="form-content">
                    <header>KUSHITIC-Login</header>
                    <form action="" method="POST">
                        <div class="field input-field">
                            <input type="text" name="username" placeholder="Username" class="input" required>
                        </div>

                        <div class="field input-field">
                            <input type="password" name="password" placeholder="Password" class="password" required>
                            <i class='bx bx-hide eye-icon'></i>
                        </div>
                        <div class="media-options">
                            <button type="submit" class="field login-btn">
                                <img src="assets/images/user.png" alt="" class="form-btn-img">
                                <span>Login</span>
                            </button>
                        </div>
                    </form>

                    <div class="form-link">
                        <span>Don't have an account? </span>
                    </div>
                </div>

                <div class="line"></div>

               

                <div class="media-options">
                    <a href="signup.php" class="field signup-btn">
                        <img src="assets/images/signup.png" alt="" class="form-btn-img">
                        <span>Signup</span>
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
                        <img src="assets/images/google.png" alt="" class="google-img">
                        <span>Login with Google</span>
                    </a>
                </div>

            </div>
        </section>
 <?php

    include('includes/footer.php');

?>