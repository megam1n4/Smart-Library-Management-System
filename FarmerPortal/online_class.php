<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in with a phone number
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in with a phone number as a farmer to post a meet and greet.";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Using phone number from the session

include("../Includes/db.php"); // Include the database connection

// Retrieve farmer_id using phonenumber
$query = "SELECT farmer_id FROM farmerregistration WHERE farmer_phone = '$phonenumber'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $farmer = mysqli_fetch_assoc($result);
    $farmer_id = $farmer['farmer_id'];
} else {
    echo "No farmer found with this phone number.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form inputs
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $meet_link = mysqli_real_escape_string($con, $_POST['meet_link']);
    $meet_date = $_POST['meet_date'];
    $meet_time = $_POST['meet_time'];

    // Insert the meet and greet details into the database with farmer_id as the identifier
    $query = "INSERT INTO meet_and_greet (farmer_id, title, description, meet_link, meet_date, meet_time) 
              VALUES ('$farmer_id', '$title', '$description', '$meet_link', '$meet_date', '$meet_time')";
    
    if (mysqli_query($con, $query)) {
        echo "<div class='alert alert-success text-center'>Meet and greet session posted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error posting meet and greet: " . mysqli_error($con) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Rare Book Exhibition</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        /* --- Navbar Styling (Simplified for Header Bar) --- */
        nav.navbar {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand img {
            height: 50px;
            width: auto;
            object-fit: contain;
            background: white;
            padding: 5px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        /* User Icons for mobile menu toggle */
        .user-icon {
            color: goldenrod;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        /* Mobile Menu Styling */
        .moblists {
            display: none;
        }

        @media (max-width: 1200px) {
            .moblists {
                display: block;
                margin-top: 20px;
            }

            .moblists .list-group-item,
            .moblists .dropdown-item {
                background-color: #1a1a2e !important;
                color: goldenrod !important;
                border: none;
                text-align: center;
                padding: 15px;
                transition: all 0.3s ease;
            }

            .moblists .list-group-item:hover,
            .moblists .dropdown-item:hover {
                background-color: #28a745 !important;
                color: white !important;
            }
        }
        
        /* --- Main Navigation Buttons (The required "button" block) --- */
        .main-nav-section {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .main-nav-btn {
            background: white;
            border: 2px solid #28a745;
            color: #28a745;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .main-nav-btn:hover {
            background: #28a745;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }
        
        /* --- Form Styling (Customized for theme) --- */
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%) !important;
            color: goldenrod !important;
            border-radius: 15px 15px 0 0 !important;
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* --- Footer Styling --- */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: 80px;
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
        }
    </style>
</head>

<body class="bg-light">
    
    <nav class="navbar navbar-expand-xl">
        <div class="container-fluid">
            <a class="navbar-brand" href="LibrarianHomepage.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>
        </div>
    </nav>
    <hr>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header card-header-custom">
                        <h3 class="mb-0 text-center">Post Rare Book Exhibition</h3>
                    </div>
                    <div class="card-body">
                        <form action="online_class.php" method="POST">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter event title">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Enter event description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="meet_link">Meet Link</label>
                                <input type="url" class="form-control" id="meet_link" name="meet_link" required placeholder="Enter meeting link">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="meet_date">Date</label>
                                    <input type="date" class="form-control" id="meet_date" name="meet_date" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="meet_time">Time</label>
                                    <input type="time" class="form-control" id="meet_time" name="meet_time" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Click Here to Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section id="footer" class="myfooter">
        <div class="container">
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>
</html>