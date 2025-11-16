<?php
include("../Includes/db.php");
session_start();
$sessphonenumber = $_SESSION['phonenumber'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Library Management System - Submit Rare Book Details</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* --- Global Body Styling (From FarmerHomepage) --- */
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0;
        }
        
        /* --- Navbar Styling (Copied from farmerHomepage.php) --- */
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

        .user-icon {
            color: goldenrod;
            font-size: 28px;
            cursor: pointer;
            position: relative;
        }

        .user-icon:hover {
            color: #ffcc66;
            transform: scale(1.1);
        }

        .btn-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

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
        
        /* --- Form Container Styling (Updated to look modern/centered) --- */
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff; 
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 50px auto;
            text-align: center;
        }
        
        .website-name {
            font-size: 28px;
            font-weight: bold;
            color: #292b2c;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        label {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: #495057;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #667eea !important;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        button {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #ffffff;
            padding: 12px;
            width: 100%;
            font-size: 17px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            transform: translateY(-2px);
        }
        .info {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
            display: block;
        }

        /* --- Footer Styling (Copied from farmerHomepage.php) --- */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: auto;
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
<body>
    
    <nav class="navbar navbar-expand-xl">
        <div class="container-fluid">
            <a class="navbar-brand" href="farmerHomepage.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>

            <div class="d-xl-none" style="display: flex; align-items: center; gap: 15px;">
                <i class='far fa-user-circle user-icon'></i>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-bars" style="color:goldenrod; font-size:28px;"></i>
            </button>
        </div>
    </nav>
    <hr>


    <div class="container">
        <div class="website-name"><h1>Smart Library Management System</h1></div>
        
        <h1>Insert Rare Book Details</h1>
        
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to submit?')">
            <div class="form-group">
                <label for="product_name">Book Name <span class="info">(e.g., Title of the Book)</span></label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="farmer_phone">Phone</label>
                <input type="text" id="farmer_phone" name="farmer_phone" value="<?php echo $sessphonenumber; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="product_image">Book Image <span class="info">(Upload an image in JPG, PNG format)</span></label>
                <input type="file" id="product_image" name="product_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="product_description">Book Description</label>
                <textarea id="product_description" name="product_description" rows="4" required placeholder="Enter a brief description..."></textarea>
            </div>

            <div class="form-group">
                <label for="bid_ending_time">Ending Time</label>
                <input type="datetime-local" id="bid_ending_time" name="bid_ending_time" required>
            </div>

            <button type="submit" name="insert_pro">Submit Book</button>
        </form>
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

<?php
if (isset($_POST['insert_pro'])) {    // when button is clicked

    // getting the text data from fields
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $lowest_bid = $_POST['lowest_bid'];
    $bid_ending_time = $_POST['bid_ending_time'];
    $farmer_phone = $_POST['farmer_phone']; // Note: This value comes from the hidden/readonly field in the form

    // getting image
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp = $_FILES['product_image']['tmp_name'];  // for server

    if (isset($_SESSION['phonenumber'])) {
        // --- CORRECTED IMAGE UPLOAD PATH based on InsertProduct.php ---
        // This line moves the uploaded image file to the specified directory on the server
        move_uploaded_file($product_image_tmp, "../Admin/product_images/$product_image");
        // ----------------------------------------------------------------

        $phone = $_SESSION['phonenumber'];
        $getting_id = "select * from farmerregistration where farmer_phone = '$sessphonenumber'"; // Added quotes for string
        $run = mysqli_query($con, $getting_id);
        $row = mysqli_fetch_array($run);
        $id = $row['farmer_id'];
        
        // Insert product details into database
        $insert_product = "insert into bid (product_name, product_description, product_image, lowest_bid, bid_ending_time, farmer_phone) 
                           values ('$product_name','$product_description','$product_image','$lowest_bid','$bid_ending_time', '$farmer_phone')";

        $insert_query = mysqli_query($con, $insert_product);

        if ($insert_query) {
            echo "<script>alert('Product has been added successfully.')</script>";
            echo "<script>window.open('farmerHomepage.php','_self')</script>";
        } else {
            echo "<script>alert('Error uploading data. Please check your connection and try again.')</script>";
        }
    }
}
?>