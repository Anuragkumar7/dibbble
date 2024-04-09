<?php

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection
    include 'connect.php';

    if (isset($_POST['submit'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if username is the same as name
        if ($username == $name) {
            $username_error = "Username cannot be the same as Name.";
        }

        // Check if the username is already taken
        $check_username_sql = "SELECT * FROM signup WHERE username='$username'";
        $result_username = mysqli_query($conn, $check_username_sql);
        if (mysqli_num_rows($result_username) > 0) {
            $username_error = "Username is already taken. Please choose a different username.";
        }

        // Check if the email is already registered
        $check_email_sql = "SELECT * FROM signup WHERE email='$email'";
        $result_email = mysqli_query($conn, $check_email_sql);
        if (mysqli_num_rows($result_email) > 0) {
            $email_error = "Email is already registered. Please use a different email address.";
        }

        // If there are no errors, proceed with inserting data into the database
        if (!isset($username_error) && !isset($email_error)) {
            // Hash the password (for better security)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO `signup`(`name`, `username`, `password`, `email`) VALUES ('$name','$username','$hashed_password','$email');";

            if (mysqli_query($conn, $sql)) {
                // Redirect to the login page after successful signup
                // After verifying the user's credentials, set the email session variable
                $_SESSION["email"] = $email; // Assuming $user_email contains the user's email
                header("Location: login.php");
                exit(); // Make sure to exit after redirection
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'link.php'; ?>
</head>

<body>
    <div class="vh-100 d-flex">
        <div class="container gradient-form" style="margin-left: 0px;">
            <div class="row vh-100">
                <!-- Hide left side on small devices -->
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
                <div class="col-md-7 pt-5">
                    <div class="d-flex flex-column ms-md-5">
                        <div class="text-right">
                            <p class="text-end mt-4 mb-0" style="font-weight: 600;">Already a member? <a href="login.php" class="text-decoration-none">Sign in</a></p>
                        </div>
                        <div class="text-start mt-md-1">
                            <h4 class="mt-1 mb-4 fw-bolder">Sign up to Dribbble</h4>
                            <?php if (isset($username_error)) echo "<p class='text-danger' id='username_error'>$username_error</p>"; ?>
                            <?php if(isset($email_error)) echo "<p class='text-danger' id='email_error'>$email_error</p>";?>
                        </div>
                        <div class="row mb-2">
                            <form action="" method="post" class="w-100">
                                <div class="col-md-12 mb-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <p class="mb-1" style="font-weight: 600;">Name</p>
                                            <input class="form-control" type="text" id="name1" name="name" placeholder="Name" required>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <p class="mb-1" style="font-weight: 600;">Username</p>
                                            <input class="form-control" type="text" id="username1" name="username" placeholder="Unique username..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <p class=" mb-1" style="font-weight: 600;">Email</p>
                                    <input class="form-control" type="email" id="form1" name="email" placeholder="example@gmail.com" required>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <p class="mb-1" style="font-weight: 600;">Password</p>
                                    <input class="form-control" type="password" id="form2" name="password" placeholder="6+ characters" required>
                                </div>
                                <div class="form-check mb-4 col-md-12">
                                    <input class="form-check-input" type="checkbox" id="agreeCheck" name="agreeCheck" required>
                                    <label class="form-check-label" for="agreeCheck">
                                        Creating an account means you're okay with our <a href="" class="text-decoration-none">Terms of Service, Privacy Policy,</a> and our default <a href="" class="text-decoration-none">Notification Settings.</a>
                                    </label>
                                </div>
                                <div class="col-md-12 pt-1 mb-4 pb-1">
                                    <button class="btn btn-primary w-100 gradient-custom-2" style="background-color: #E34D8A; border-color: #E34D8A;" name="submit">Create Account</button>
                                    <?php if (isset($sql)) echo "<p class='text-danger' id='success_msg'>$succ</p>" ?>
                                </div>
                            </form>
                            <div class="d-flex flex-row align-items-center justify-content-center pb-2 mb-4">
                                <p>This site is protected by reCAPTCHA and the Google <a href="" class="text-decoration-none">Privacy Policy</a>, and our default <a href="" class="text-decoration-none">Notification Settings.</a></p>
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
                }, 3000); // Adjust the timeout duration (in milliseconds) as needed
            }
            if (document.getElementById('email_error')) {
                setTimeout(function() {
                    document.getElementById('email_error').style.display = 'none';
                }, 3000); // Adjust the timeout duration (in milliseconds) as needed
            }
            if (document.getElementById('success_msg')) {
                setTimeout(function() {
                    document.getElementById('success_msg').style.display = 'none';
                }, 3000); // Adjust the timeout duration (in milliseconds) as needed
            }
        };
    </script>
</body>

</html>
