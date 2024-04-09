<?php
session_start();
 
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection
    include 'connect.php';

    if (isset($_POST['submit'])) {
        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the username exists in the database
        $check_username_sql = "SELECT * FROM signup WHERE username='$username'";
        $result = mysqli_query($conn, $check_username_sql);
        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $user_data['password'])) {
                // Password is correct, set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $name;
                $_SESSION['user_id'] = $user_data['user_id']; // Set the user_id session variable
                $_SESSION['email'] = $user_data['email'];
                // Redirect to profile creation page
                header("Location: home.php");
                exit();
            } else {
                $login_error = "Invalid username or password.";
            }
        } else {
            $login_error = "Invalid username or password.";
        }
    }
    
}
?>

<?php

// Check if email session variable is set
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    // Email session variable not set, handle accordingly
    $email = "Email not available"; // You can set a default value or display a message
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'link.php'; ?>
    <style>

        @media (max-width: 767.98px) {
            .form-container {
                margin-left: 0 !important;
            }
            .gradient-form {
                margin-top: 0 !important;
            }
            .text-end {
                margin-top: 20px;
            }
            .pt-1 {
                margin-left: 0 !important;
                width: 100%;
            }
            .mb-4,
            .pb-2 {
                margin-left: 0 !important;
            }
            .text-start {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="vh-100 d-flex">
        <div class="container gradient-form" style="
    margin-left: 0px;">
            <div class="row vh-100">
                <div class="col-md-5 d-none d-md-block" style="background-color: #F2D184;">
                    <div class="d-flex flex-column justify-content-center gradient-custom-2 h-100 mb-4">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <h1 class="fw-bold" style="color: #A9893B; font-family: 'Butterfly Kids', cursive;">Dribbble</h1>
                            <h4 class="mb-4">Discover the world's top Designers & Creatives.</h4>
                            <img style="width: 100%; mix-blend-mode: multiply;" src="images/19198997.jpg" alt="">
                            <p class="mt-3" style="color: #A9893B;">Art by <a href="" style="color: #A9893B;">Peter Tarka</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 form-container pt-5">
                    <div class="d-flex flex-column ms-md-5">
                        <div class="text-right">
                            <p class="text-end mt-4 mb-0" style="font-weight: 600;">Don't have an account? <a href="signup.php" class="text-decoration-none">Sign up</a></p>
                        </div>
                        <div class="text-start mt-md-1">
                            <h4 class="mt-1 mb-4 fw-bolder">Login to Dribbble</h4>
                            <?php if (isset($login_error)) echo "<p class='text-danger'>$login_error</p>"; ?>
                        </div>
                        <div class="row mb-2">
                            <form action="" method="post" class="w-100">
                                <div class="col-md-12 mb-4">
                                    <p class="mb-1" style="font-weight: 600;">Username</p>
                                    <input class="form-control" type="text" id="username1" name="username" placeholder="Your username..." required>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <p class="mb-1" style="font-weight: 600;">Password</p>
                                    <input class="form-control" type="password" id="form2" name="password" placeholder="Your password..." required>
                                </div>
                                <div class="pt-1 mb-4 pb-1">
                                    <button class="btn btn-primary w-100 gradient-custom-2" style="background-color: #E34D8A; border-color: #E34D8A; " name="submit">Login</button>
                                </div>
                            </form>
                            <div class="d-flex flex-row align-items-center justify-content-center pb-2 mb-4">
                                <p class="text-center m-0">This site is protected by reCAPTCHA and the Google <a href="" class="text-decoration-none">Privacy Policy</a>, and our default <a href="" class="text-decoration-none">Notification Settings.</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        // Set timeout for error messages
        window.onload = function() {
            if (document.getElementById('username_error')) {
                setTimeout(function() {
                    document.getElementById('username_error').style.display = 'none';
                }, 2000); // Adjust the timeout duration (in milliseconds) as needed
            }
            if (document.getElementById('success_msg')) {
                setTimeout(function() {
                    document.getElementById('success_msg').style.display = 'none';
                }, 2000); // Adjust the timeout duration (in milliseconds) as needed
            }
        };
    </script>
</body>

</html>
