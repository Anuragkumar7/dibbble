<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dribbble Profile</title>
    <?php include 'link.php'; ?>
    <style>
        .card:hover {
            transform: translateY(-10px);
            border: solid #E34D8A 2px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .centerimg:hover {
            transform: translateY(-30px);
            transition: all 0.3s ease;
        }
        .hide {
            display: none;
        }
        .centerimg:hover + .hide {
            display: block;
            position: relative;
            top: -10px; 
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-2" href="#" style=" font-family: 'Butterfly Kids', cursive;
            font-weight: 400;
            font-style: normal; color:#E34D8A;">Dribbble</a>
            <a href="profile.php" class="btn btn-link mb-3" style="margin: 20px 1400px 0 0; box-shadow: 20px;  color: grey; background-color: lightgrey;"><i class="fa-solid fa-less-than"></i></a>
        </div>
        <!-- <div class="col text-center me-4">
            <a href="logout.php" class="btn btn-danger" style="background-color: #E34D8A;">Logout</a>
        </div> -->
    </nav>
    <div class="container my-5">
        <div class="card-body">
            <h2 class="card-title mb-4">What brings you to Dribbble?</h2>
            <p class="card-text text-muted mb-4">Select the options that best describe you. Don't worry, you can explore other options later.</p>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                          <img src="images/image1.png" alt="Designer Share Work" class="img-fluid mb-3 centerimg" style="width: 300px;">
                          <div class="hide">
                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus aspernatur culpa quo quam doloremque alias, optio iste? Accusamus, inventore veritatis?</p>
                          </div>
                          <p class="card-text mb-0 ">I'm a designer looking to share my work</p>
                            <input type="checkbox" name="option" onclick="uncheckOthers(this)" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="images/image2.png" alt="Hire Designer" class="img-fluid mb-3 centerimg" style="width: 300px;">
                            <div class="hide">
                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus aspernatur culpa quo quam doloremque alias, optio iste? Accusamus, inventore veritatis?</p>
                          </div>
                            <p class="card-text mb-0">I'm looking to hire a designer</p>
                            <input type="checkbox" name="option" onclick="uncheckOthers(this)" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="images/image3.png" alt="Design Inspiration" class="img-fluid mb-3 centerimg" style="width: 300px;">
                            <div class="hide">
                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus aspernatur culpa quo quam doloremque alias, optio iste? Accusamus, inventore veritatis?</p>
                          </div>
                            <p class="card-text mb-0">I'm looking for design inspiration</p>
                            <input type="checkbox" name="option" onclick="uncheckOthers(this)" required>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-muted mb-4">Anything else? You can select multiple</p>
            <div class="text-center">
              <a href="home.php">

                <button class="btn btn-primary" style="padding: 5px 60px; background-color:#E34D8A; border-color:#E34D8A;" onclick="finish()">Finish</button>
              </a>
                <p class="mt-3 mb-0">or Press RETURN</p>
            </div>
        </div>
    </div>
   
</body>

</html>
