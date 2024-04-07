<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the database configuration file 
include_once 'connect.php';

$statusMsg = '';

// File upload directory 
$targetDir = "uploads/";

if (isset($_POST["submit"])) {
    if (!empty($_FILES["file"]["name"])) {
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats 
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server 
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                // Insert image file name into database 
                $insert = mysqli_query($conn, "INSERT INTO images (file_name, uploaded_on) VALUES ('" . $fileName . "', NOW())");
                if ($insert) {
                    $statusMsg = "The file " . $fileName . " has been uploaded successfully.";
                } else {
                    $statusMsg = "File upload failed, please try again.";
                }
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
        }
    } else {
        $statusMsg = 'Please select a file to upload.';
    }
}

// Get the latest uploaded image from the database for the logged-in user
$name = $_SESSION["username"];
// $email = $_SESSION["email"];
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Butterfly+Kids&display=swap" rel="stylesheet">
  <style>
    .avatar-container {
      display: flex;
      align-items: center;
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
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg ">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-2" href="#" style=" font-family: 'Butterfly Kids', cursive;
  font-weight: 400;
  font-style: normal; color:#E34D8A;">Dribbble</a>
    </div>
    <!-- <div class="col text-center me-4">
        <a href="logout.php" class="btn btn-danger" style="background-color: #E34D8A;">Logout</a>
    </div> -->
</div>  
  </nav>
  <div class="container my-4">
    <div class="card border-0" style="padding: 0 100px;">
      <div class="card-body">
        <div class="row">
          <div class="col text-center">
            <h2 class="card-title mb-4 fw-bold">Welcome! Let's create your profile</h2>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <p class="card-text text-muted mb-4" style="margin-right: 100px;">Let others get to know you better! You can do these later</p>
          </div>
        </div>
        <div class="row avatar-container">
          <div class="col fw-bold" style="margin-left: 280px;">
            <h4>Hello <?php  echo $name ?></h4>
            <p>Add an avatar</p>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <div class="avatar-container mb-4">
              <!-- Display the latest uploaded image or default avatar -->
              <div id="avatar" class="avatar mr-3" style="margin-left: 290px;">
                <img src="<?php echo $imageURL; ?>" style="width: 130px; height: 130px; border-radius: 50%;" alt="Avatar">
              </div>
              <div class="d-flex flex-column">
                <div class="mb-2">
                  <!-- Form element for file upload -->
                  <form action="" method="post" enctype="multipart/form-data">
                    <!-- Label for file input -->
                    <!-- Actual file input (hidden) -->
                    <input type="file" name="file" id="file-upload">
                    <!-- Submit button -->
                    <label for="file-upload" class="btn btn-outline-danger">Choose image</label>
                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                  </form>
                </div>
                <div class="ms-4">
                  <small class="text-muted ms-4 fw-bold" style="font-size: small; "><i class="fa-solid fa-greater-than me-1" style="font-size: 10px; "></i>Or choose one of our defaults</small>
                  <!-- // Display status message  -->
                </div>
              </div>
            </div>
            <p id="statusMsg" style="color: green;"> <?php echo $statusMsg ?> </p>
          </div>
          <div class="row">
            <div class="col text-center">
              <div class="form-group">
                <label for="location" class="fw-bold fs-5 " style="margin: 50px 290px 20px 0px;">Add your location</label>
                <input type="text" class="form-control mb-3" style="width: 50%; margin-left: 290px; border: 0; box-shadow: none;" id="location" placeholder="Enter a location">
                <hr style="width: 50%; margin-left: 290px;">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col text-center">
             <a href="card.php">
                <button type="submit" name="submit" class="btn btn-primary px-5" style="background-color: #E34D8A; border-color: #E34D8A;">Next</button>
             </a>   
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
