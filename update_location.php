<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the database configuration file 
include_once 'connect.php';

$email = $_SESSION["email"];

$statusMsg = '';

// File upload directory 
$targetDir = "uploads/";

// Function to sanitize and validate input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the location form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["location"])) {
    // Sanitize and validate the location input
    $location = sanitizeInput($_POST["location"]);

    // Update location in the database
    $update = mysqli_query($conn, "UPDATE user_locations SET location = '$location' WHERE email = '$email'");
    
    if ($update) {
        // Update location in the session variable
        $_SESSION['location'] = $location;
        $statusMsg = "Location updated successfully.";
    } else {
        $statusMsg = "Failed to update location. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'link.php'; ?>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Update Location</div>
                    <div class="card-body">
                        <?php if (!empty($statusMsg)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $statusMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group mb-3">
                                <label for="location">New Location</label>
                                <input type="text" class="form-control" id="location" name="location" >
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            
                        </form>
                        <div class="px-2 mt-2" style="background-color: #E34D8A; display:block ; width: 120px; border-radius:2px;">

                            <a href="profile.php" >
                                <i class="fa-solid fa-less-than " style="color: white;"> Go Back</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
