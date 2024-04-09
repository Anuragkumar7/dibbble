<?php
include 'connect.php';

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

// Get user's email from session
$email = $_SESSION["email"];

// If the form to update email is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_email'])) {
    $newEmail = $_POST['new_email'];

    // Check if the new email is already associated with another user
    $check_email_sql = "SELECT * FROM signup WHERE email='$newEmail'";
    $check_email_result = mysqli_query($conn, $check_email_sql);

    if (mysqli_num_rows($check_email_result) > 0) {
        $verificationSuccess = false;
        $verificationMessage = "Email address '$newEmail' is already associated with another account.";
    } else {
        // Update the email address in the database
        $update_email_sql = "UPDATE signup SET email='$newEmail' WHERE email='$email'";
        $update_result = mysqli_query($conn, $update_email_sql);

        if ($update_result) {
            // Update the session with the new email
            $_SESSION["email"] = $newEmail;

            // Resend the verification email to the new email address
            // Generate a new verification code
            $verificationCode = uniqid();

            // Update the database with the new verification code
            $update_verification_code_sql = "UPDATE signup SET verification_code='$verificationCode', verification_sent=1 WHERE email='$newEmail'";
            mysqli_query($conn, $update_verification_code_sql);

            // Send verification email to the new email address
            $mail = new PHPMailer(true);

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
                $mail->addAddress($newEmail); // Add a recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Verify Your New Email Address';
                $mail->Body    = 'Your verification code for the new email address is: ' . $verificationCode;

                $mail->send();
                $verificationMessage = 'Verification email sent successfully to your new email address.';
            } catch (Exception $e) {
                $verificationSuccess = false;
                $verificationMessage = "Verification email could not be sent to your new email address. Mailer Error: {$mail->ErrorInfo}";
            }

            // Redirect to home.php after successful email update
            header("Location: home.php");
            exit;
        } else {
            $verificationSuccess = false;
            $verificationMessage = "Failed to update email address: " . mysqli_error($conn);
        }
    }
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
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Update Email Address</h5>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="new_email" class="form-label">New Email Address</label>
                                <input type="email" class="form-control" id="new_email" name="new_email" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="background-color: #E34D8A; border-color: #E34D8A;">Update Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
