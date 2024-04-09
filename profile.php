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
function sanitizeInput($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$inserted = false;

// Check if the location has already been saved in the session
$userLocation = isset($_SESSION['location']) ? $_SESSION['location'] : '';

// Check if the location form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["location"])) {
  // Sanitize and validate the location input
  $location = sanitizeInput($_POST["location"]);

  // Insert location into the database
  $insert = mysqli_query($conn, "INSERT INTO user_locations (user_id, location, email) VALUES ('" . $_SESSION['user_id'] . "', '$location', '$email')");

  if ($insert) {
    $inserted = true;
    // Save the location in the session variable
    $_SESSION['location'] = $location;
    $statusMsg = "Location saved successfully.";
  } else {
    $inserted = false;
    $statusMsg = "Failed to save location. Please try again.";
  }
}

// Get the latest uploaded image from the database for the logged-in user
//$name = $_SESSION['name'];
//$_SESSION['name'] = $row['name'];
$name = $_SESSION['username'];
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-qJ/VR6Z0aaqI5mg08t3O/y92KvG7j7kcs6+e3TZ8M9LfmdB+oHJU6bqz8+8peJRb" crossorigin="anonymous">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&display=swap" rel="stylesheet">
  <style>
    .avatar-container {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .avatar {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      background-color: #f8f9fa;
      border: 2px dashed #ced4da;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .btn-outline-danger {
      color: black;
      border-color: lightgray;
    }

    /* Add this CSS block */
    .btn-outline-danger:hover {
      background-color: #E34D8A;
      color: white;
      /* Change this color to whatever you want on hover */
    }

    /* Hide the file input */
    input[type="file"] {
      display: none;
    }

    @media (max-width: 767.98px) {
      .colavt{
        margin-left: 20px !important;
      }
   
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg ">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-2" href="#" style=" font-family: 'Butterfly Kids', cursive;
  font-weight: 400;
  font-style: normal; color:#E34D8A;">Dribbble</a>
    </div>
  </nav>
  <div class="container">
    <div class="card border-0" style="padding: 0 100px;">
      <div class="card-body">
        <div class="row">
          <div class="col text-center">
            <h2 class="card-title mb-4 fw-bold">Welcome! Let's create your profile</h2>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <p class="card-text text-muted mb-4" style="margin-right: 100px;">Let others get to know you
              better! You can do these later</p>
          </div>
        </div>
        <div class="row avatar-container">
          <div class="col fw-bold colavt" style="margin-left: 280px; margin-right: auto;">
            <h4> Hello <?php  echo $name; ?></h4>
            <p>Add an avatar</p>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <div class="avatar-container mb-4">
              <!-- Display the latest uploaded image or default avatar -->
              <div id="avatar" class="avatar mr-3" style="margin-left: 90px; margin-right: 20px;">
                <img src="<?php echo $imageURL; ?>" style="width: 130px; height: 130px; border-radius: 50%;" alt="Avatar">
              </div>
              <div class="d-flex flex-column align-items-start">
                <div class="mb-2">
                  <!-- Form element for file upload -->
                  <form action="" method="post" enctype="multipart/form-data">
                    <!-- Label for file input -->
                    <!-- Actual file input (hidden) -->
                    <input type="file" name="file" id="file-upload">
                    <!-- Submit button -->
                    <label for="file-upload" class="btn btn-outline-danger">Choose
                      image</label>
                    <button type="submit" name="submit" class="btn btn-primary ">Upload</button>
                  </form>
                </div>
                <div class=" align-items-start">
                  <small class="text-muted fw-bold choose" style="font-size: small; margin-right: 220px;"><i class="fa-solid fa-greater-than me-1" style="font-size: 10px; "></i>Or choose one of our
                    defaults</small>
                </div>
              </div>
            </div>
            <p id="statusMsg" style="color: green;"> <?php echo $statusMsg ?> </p>
          </div>
        </div>
        <?php if ($userLocation) : ?>
              <div class="text-center">
                <h3>Location</h3>
                <h5><?php echo $userLocation; ?></h5>
                <a href="Hire.php"> <button type="submit" class="btn btn-primary px-5 mt-2" style="background-color: #E34D8A; border-color: #E34D8A;">Next</button>
                </a>
                <a href="update_location.php" ><button class="btn btn-primary  px-4" style="text-decoration: none; background-color:#E34D8A; border:#E34D8A; margin-top:7px;">Update Location</button></a>
              </div>
            <?php else : ?>
              <div class="row">
                <div class="col text-center">
                  <div class="form-group">
                    <label for="location" class="fw-bold fs-5 " style="margin: 50px auto 20px auto;">Add your location</label>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <input type="text" class="form-control mb-3" style="width: 50%; margin: 0 auto; border: 0; box-shadow: none;" id="location" placeholder="Enter a location" name="location" required>
                      <hr style="width: 50%; margin: 0 auto;">
                      <button type="submit" class="btn btn-primary px-5 mt-2" style="background-color: #E34D8A; border-color: #E34D8A;">Save Location</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endif; ?>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    // Set timeout to hide the error message after 2 seconds
    setTimeout(function() {
      document.getElementById('statusMsg').style.display = 'none';
    }, 2000);
  </script>
</body>

</html>