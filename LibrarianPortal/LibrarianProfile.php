<?php
include("../Includes/db.php");
session_start();
$sessphonenumber = $_SESSION['phonenumber'];
$sql = "select * from librarianregistration where farmer_phone = '$sessphonenumber' ";
$run_query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($run_query)) {
    $name = $row['farmer_name'];
    $phone = $row['farmer_phone'];
    $address = $row['farmer_address'];
    $pan = $row['farmer_pan'];
    $bank = $row['farmer_bank'];
    $state = $row['farmer_state'];
    $district = $row['farmer_district'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Profile</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>

    <style>
        /* Base and Profile Styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex; /* Added for footer sticky */
            flex-direction: column; /* Added for footer sticky */
        }

        .profile-card {
            max-width: 700px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            flex-grow: 1; /* Allow content to push footer down */
        }

        .profile-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 30px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group-prepend .text {
            min-width: 150px;
            font-weight: 600;
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            color: goldenrod;
            font-size: 1rem;
            padding: 12px 15px;
            border-right: 1px solid #ffc107;
            display: flex;
            align-items: center;
        }
        
        .form-control-plaintext {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 1rem;
            color: #495057;
            text-align: left;
            border-left: none;
            flex-grow: 1;
        }
        
        .form-control-plaintext:focus {
             background-color: #ffffff;
        }

        .input-group .form-control-plaintext {
             border: none !important;
        }


        .btn-edit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        /* --- Top Logo Bar Styling (From donate_book.php) --- */
        .top-logo-bar {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            padding: 10px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            z-index: 100;
        }
        
        .top-logo-bar a {
            display: inline-block;
        }
        
        .top-logo-bar img {
            height: 50px;
            width: auto;
            object-fit: contain;
            background: white;
            padding: 5px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .top-logo-bar img:hover {
            transform: scale(1.05);
        }

        /* --- Footer Styling (From donate_book.php) --- */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: auto; /* Push footer to the bottom */
        }

        .myfooter h5 {
            color: #ffc107;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .myfooter img {
            margin: 10px;
            border-radius: 8px;
            max-height: 35px;
            width: auto;
        }

        .social li a {
            color: #ffc107;
            font-size: 24px;
            transition: all 0.3s ease;
        }
        
        .social li a:hover {
            color: #28a745;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .profile-card {
                padding: 20px;
                border-radius: 15px;
            }
            .profile-header h2 {
                font-size: 1.75rem;
            }
            .input-group-prepend .text {
                min-width: 120px;
                font-size: 0.9rem;
            }
            .form-control-plaintext {
                font-size: 0.9rem;
            }
            .btn-edit {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="top-logo-bar">
        <a href="LibrarianHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo">
        </a>
    </div>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h2><i class="fas fa-user-circle mr-2"></i>Your Profile</h2>
            </div>
            <div class="form">
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-user mr-2"></i>Full Name</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $name?>">
                </div>
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-phone-alt mr-2"></i>Phone No.</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $phone ?>">
                </div>
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-home mr-2"></i>Address</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $address ?>">
                </div> 
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-globe-americas mr-2"></i>State</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $state ?>">
                </div> 
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-city mr-2"></i>City</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $district ?>">
                </div> 
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-pencil-alt mr-2"></i>Pan No.</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $pan ?>">
                </div> 
                
                <div class="input-group">
                    <div class="input-group-prepend ">
                        <span class="input-group-text text"><i class="fas fa-university mr-2"></i>Account No.</span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $bank ?> ">
                </div> 
            </div>
            
            <button type="button" class="btn btn-edit d-flex mx-auto btn-lg" onclick="navigateToEditPage()">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </button>

            <script>
            function navigateToEditPage() {
                // Ensure this link is correct for your Edit page
                window.location.href = "EditProfile.php"; 
            }
            </script>
        </div>
    </div>

    <section id="footer" class="myfooter">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                    <ul class="list-unstyled list-inline social text-center">
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-google-plus"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();" target="_blank"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center">
                    <p>Smart Library Management System is a Online Management System for Book Lovers!</p>
                    <p class="h6"><a class="text-green ml-2" target="_blank">Foreign Key Friends</a></p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>