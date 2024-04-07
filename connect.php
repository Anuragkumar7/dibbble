<?php
// Database connection parameters
$servername = "localhost";
    $username = "root";
    $password = "";
    $database = "profile";

    $conn = mysqli_connect($servername, $username, $password, $database);



    if (!$conn) {
       
        die("Connection failed: " . mysqli_connect_error());
    }
    ?>
