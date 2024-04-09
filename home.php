<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer autoload.php

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include database connection
include 'connect.php';

// Get user's email from session
$email = $_SESSION["email"];

// Check if the email is already verified
$check_verified_sql = "SELECT verified FROM signup WHERE email = '$email'";
$result = mysqli_query($conn, $check_verified_sql);
$row = mysqli_fetch_assoc($result);
$verified = $row['verified'];

// Check if verification code has been sent
$check_verification_sent_sql = "SELECT verification_sent FROM signup WHERE email = '$email'";
$result = mysqli_query($conn, $check_verification_sent_sql);
$row = mysqli_fetch_assoc($result);
$verificationSent = $row['verification_sent'];

// If email is not verified and verification code has not been sent, proceed to send verification email
if (!$verified && !$verificationSent) {
    // Generate a random verification code
    $verificationCode = uniqid();

    // Update the database with the verification code
    $update_verification_code_sql = "UPDATE signup SET verification_code='$verificationCode', verification_sent=1 WHERE email='$email'";
    mysqli_query($conn, $update_verification_code_sql);

    // Send verification email
    $mail = new PHPMailer(true);

    $verificationSuccess = true;
    $verificationMessage = "";

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['HOST']; // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['EMAIL']; // SMTP username
        $mail->Password   = $_ENV['APP_PASS']; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587; // SMTP port

        //Recipients
        $mail->setFrom($_ENV['EMAIL'], 'Dribbble');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = 'Your verification code is: ' . $verificationCode;

        $mail->send();
        $verificationMessage = 'Verification email sent successfully.';
    } catch (Exception $e) {
        $verificationSuccess = false;
        $verificationMessage = "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // If email is already verified or verification code has already been sent, set verification success to true
    $verificationSuccess = true;
    $verificationMessage = $verified ? "Email already verified." : "Verification email already sent.";
}


$query = mysqli_query($conn, "SELECT * FROM images ORDER BY uploaded_on DESC LIMIT 1");

$imageURL = '';

if ($query->num_rows > 0) {
    // Fetch the latest uploaded image
    $row = $query->fetch_assoc();
    $imageURL = 'uploads/' . $row["file_name"];
} else {
    // If no profile picture uploaded, show default picture
    $imageURL = 'default_profile_picture.jpg'; // Change to the path of your default profile picture
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'link.php';
    ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h2 style="color:#E34D8A; font-family: 'Butterfly Kids', cursive;
                               font-weight: 400;
                                font-style: normal;">dribbble</h2>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" aria-current="page" href="#">Inspiration</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#">Find Work</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#">Find Work</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#">Learn Design</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#">Go Pro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="">Hire Designers</a>
                    </li>
                  
                </ul>

                <form class="d-flex" role="search">

                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <i class="fa-solid fa-business-time fa-2x me-3"></i>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle me-3 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $imageURL ?>" class="border border-secondary me-3" style="width: 35px; border-radius: 50%; " alt="...">
                            
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="card.php">Hire</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-primary" style="background-color: #E34D8A; border-color:#E34D8A;" type="submit">Upload</button>
                </form>
            </div>
        </div>
    </nav>

    <?php if ($verified == 0) : ?>
        <main class="container my-4">
            <div class="row justify-content-center">
                <div class="">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="card-title mb-4">Please verify your email...</h2>
                            <p class="card-text">
                                <i class="fa-solid fa-envelope-circle-check fa-6x" style="color: #E34D8A;"></i>
                            </p>
                            <p class="card-text">Please verify your email address. We've sent a confirmation email to:</p>
                            <p class="card-text mb-4"><strong><?php echo $email; ?></strong></p>
                            <form action="" method="post">
                                <label for="verification_code">Enter Verification Code:</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control " style="margin: 5px 500px;" id="verification_code" name="verification_code" required aria-label="Text input with checkbox">
                                </div>
                                <!-- <input type="text" class="flow-control" id="verification_code" name="verification_code" required> -->
                                <button type="submit" class="btn btn-primary" style="background-color: #E34D8A; border-color:#E34D8A;">Verify</button>
                            </form>

                            <p class="card-text">Click the confirmation link in that email to begin using Dribbble.</p>
                            <p class="card-text">Didn't receive the email? Check your Spam folder, it may have been caught by a filter. If you still don't see it, you can <a href="#" class="fw-bold" style="text-decoration: none; color:#E34D8A;">resend the confirmation email</a>.</p>
                            <p class="card-text">Wrong email address? <a href="newmail.php" class="fw-bold" style="text-decoration: none; color:#E34D8A;">Change it</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $verificationMessage;
            ?>
        </div>
    <?php endif; ?>


    <footer class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <h2>Dribbble</h2>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. </p>
                    <a href="" style="color: black; text-decoration: none;">

                        <i class="fa-solid fa-basketball me-2"></i>
                    </a>
                    <a href="" style="color: black; text-decoration: none;">

                        <i class="fa-brands fa-twitter me-2"></i>
                    </a>
                    <a href="" style="color: black; text-decoration: none;">

                        <i class="fa-brands fa-instagram me-2"></i>
                    </a>
                    <a href="" style="color: black; text-decoration: none;">

                        <i class="fa-brands fa-square-facebook me-2"></i>
                    </a>
                    <a href="" style="color: black; text-decoration: none;">

                        <i class="fa-brands fa-pinterest me-2"></i>
                    </a>
                </div>
                <div class="col-md-2 ">

                    <p class="fw-bold">For designers</p>
                    <ul class="list-unstyled">
                        <li><a style="text-decoration: none; color: gray;" href="#">Go Pro!</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Explore design work</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Design blog</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Overtime podcast</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Playoffs</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Weekly Warm-up</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Refer a Friend</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Code of conduct</a></li>

                    </ul>
                </div>
                <div class="col-md-2">
                    <p class="fw-bold">Hire designers</p>
                    <ul class="list-unstyled">
                        <li><a style="text-decoration: none; color: gray;" href="#">Post a job opening</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Post a freelance project</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Search for designers</a></li>
                        <p class="fw-bold">Brands</p>
                        <li><a style="text-decoration: none; color: gray;" href="#">Advertise with us</a></li>

                    </ul>
                </div>
                <div class="col-md-2">
                    <p class="fw-bold">Company</p>
                    <ul class="list-unstyled">
                        <li><a style="text-decoration: none; color: gray;" href="#">About</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Careers</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Support</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Media kit</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Testimonials</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">API</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Terms of service</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Privacy policy</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Cookie policy</a></li>

                    </ul>
                </div>
                <div class="col-md-2">
                    <p class="fw-bold">Directories</p>
                    <ul class="list-unstyled">
                        <li><a style="text-decoration: none; color: gray;" href="#">Design jobs</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Designers for hire</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Freelance designers for hire</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Tags</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Places</a></li>
                        <p class="fw-bold">Design assets</p>
                        <li><a style="text-decoration: none; color: gray;" href="#">Dribbble Marketplace</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Creative Market</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Fontsping</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Font Squirrel</a></li>

                    </ul>
                </div>
                <div class="col-md-2">
                    <p class="fw-bold">Design Resources</p>
                    <ul class="list-unstyled">
                        <li><a style="text-decoration: none; color: gray;" href="#">Freelancing</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Design Hiring</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Design Portfolio</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Design Education</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Creative Process</a></li>
                        <li><a style="text-decoration: none; color: gray;" href="#">Design Industry Trend</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>