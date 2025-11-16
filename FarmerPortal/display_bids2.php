<?php
// Include your database connection code
include("../Includes/db.php");

// Start the session
session_start();

// --- START PHP Logic (Kept Intact) ---

if (isset($_POST['login'])) {
    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Authentication code (assuming you have this set up correctly)
    if ($count_rows == 1) {
        $_SESSION['phonenumber'] = $phonenumber;
    } else {
        echo "<script>alert('Please Enter Valid Details');</script>";
        echo "<script>window.open('FarmerLogin.php','_self')</script>";
    }
}

if (isset($_SESSION['phonenumber'])) {
    $auth_phonenumber = $_SESSION['phonenumber'];
    $query = "SELECT b.bid_id, b.product_id, b.bid_amount, b.farmer_phone, b.buyer_address
              FROM bids AS b
              WHERE b.farmer_phone = '$auth_phonenumber'";
    $result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Priority List of Users</title>
    
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
            background: #f8f9fa; /* Changed to match site theme */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0; 
        }

        /* --- Navbar Styling (Simplified from farmerHomepage.php) --- */
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
            transition: all 0.3s ease;
            position: relative;
        }

        .user-icon:hover {
            color: #ffcc66;
            transform: scale(1.1);
        }
        
        .container {
            max-width: 900px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 50px auto; 
        }

        /* Heading Styling (Adjusted for site theme) */
        h1 {
            text-align: center;
            color: #292b2c;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }

        /* Table Styling (Adjusted for site theme) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            border: 1px solid #e0e0e0;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #292b2c;
            color: goldenrod;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }

        /* No Bidders Message */
        .no-bidders {
            text-align: center;
            font-size: 18px;
            color: #666;
            padding: 20px 0;
        }
        
        /* --- Footer Styling (Copied from farmerHomepage.php) --- */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: auto; /* Push footer to bottom */
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
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                </div>
        </div>
    </nav>
    
    <div class="container">
        <h1>View Priority List of Users</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Book ID</th>
                <th>Phone</th>
                <th>User Address</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['bid_id']}</td>
                        <td>{$row['product_id']}</td>
                        <td>{$row['farmer_phone']}</td>
                        <td>{$row['buyer_address']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-bidders'>No users currently requested any of your books.</td></tr>";
            }

            // Close the database connection
            mysqli_close($con);
            ?>
        </table>
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
} // Close the if(isset($_SESSION['phonenumber'])) condition
?>