<?php
     include("../Functions/functions.php");
     ?>

<!DOCTYPE html>

<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Librarian - My Books</title>
     
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
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

          /* --- Navbar Styling --- */
          nav.navbar {
               background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
               padding: 15px 30px;
               box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
               position: sticky;
               top: 0;
               z-index: 1000;
          }

          /* Logo Styling */
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

          /* Placeholder for old text logo on mobile if needed */
          .book-corner-logo {
               color: goldenrod !important;
               font-weight: 800;
               font-size: 28px !important;
          }

          /* User Icons */
          .user-icon {
               color: goldenrod;
               font-size: 28px;
               cursor: pointer;
               transition: all 0.3s ease;
          }

          .user-icon:hover {
               color: #ffcc66;
               transform: scale(1.1);
          }

          /* Dropdown Button */
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

          /* Mobile List Styling */
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

          /* --- Main Navigation Buttons (Replaces old .col-md-3 links) --- */
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

          /* --- Content & Product Styling --- */
          .content_item {
               text-align: center;
               margin-bottom: 30px;
               padding-top: 20px;
          }

          .content_item label {
               font-size: 2.5rem;
               font-weight: 800;
               color: #1a1a2e;
               text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
          }
          
          .btn-add-new {
               background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
               color: #1a1a2e;
               font-weight: 700;
               border: none;
               border-radius: 10px;
               padding: 12px 25px;
               box-shadow: 0 4px 10px rgba(255, 165, 0, 0.3);
               transition: all 0.3s ease;
          }

          .btn-add-new:hover {
               transform: translateY(-2px);
               box-shadow: 0 8px 15px rgba(255, 165, 0, 0.4);
               color: #1a1a2e;
          }


          /* Product Box Grid */
          .products {
               display: flex;
               flex-wrap: wrap;
               justify-content: center;
               gap: 30px;
               padding: 20px 0;
               margin: 0 auto;
               max-width: 1200px;
          }

          .productbox {
               /* Resetting old float styles for modern flex layout */
               float: none; 
               margin: 0; 
               padding: 20px;
               border: 2px solid #e0e0e0;
               border-radius: 15px;
               width: 300px; /* Fixed width for better grid alignment */
               box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
               transition: all 0.3s ease;
          }

          .productbox:hover {
               border-color: #28a745;
               box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
               transform: translateY(-5px);
          }

          .productbox img {
               height: 250px;
               width: 100%;
               object-fit: cover;
               border: 2px solid #555;
               border-radius: 10px;
               margin-bottom: 15px;
               transition: all 0.3s ease;
          }

          .productbox img:hover {
               border-color: #28a745;
               transform: scale(1.02);
          }

          .productbox p {
               text-align: center;
               font-weight: 600;
               font-size: 1.1rem;
               color: #1a1a2e;
               text-decoration: none;
               margin-bottom: 0;
          }
          
          /* --- Footer --- */
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

          .list-inline-item a {
               color: #ffc107;
               font-size: 24px;
               transition: all 0.3s ease;
          }

          .list-inline-item a:hover {
               color: #28a745;
               transform: scale(1.2);
          }
          
          /* Cleanup of unused original styles */
          .left, .right { display: none; }
          .left { display: flex; } /* Re-enabling for mobile view */
          .right { display: none; }
          
          @media (min-width: 1200px) {
              .left { display: none; }
              .right { display: flex; }
              .d-none { display: none !important; }
              .d-xl-flex { display: flex !important; }
          }
          
     </style>

</head>

<body>

    <nav class="navbar navbar-expand-xl">
        <div class="flex-row-reverse d-xl-none left">
            <div class="p-2"></div>
            <a class="float-left" href="farmerHomepage.php">
                <h1 class="book-corner-logo" style="font-size: 25px; margin: 0; padding: 0;">
                    Smart Library Management System 
                </h1>
            </a>
        </div>
        
        <a class="navbar-brand d-none d-xl-block" href="farmerHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo">
        </a>


        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars p-1" style="color:goldenrod;font-size:20px;"></i></span>
        </button>
        
        <a class="navbar-brand d-xl-none moblogo" href="farmerHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo" style="height: 35px;">
        </a>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <div class="proicon">
                <?php
                if (!isset($_SESSION['phonenumber'])) {
                    // Removed old text-success logins div
                }
                ?>
            </div>
            
            <?php getFarmerUsername(); ?>

            <div class="list-group moblists">
                <?php
                if (isset($_SESSION['phonenumber'])) {
                    echo "<a href='FarmerProfile2.php' class='list-group-item list-group-item-action ' >Profile</a>"; // Updated link for consistency
                    echo "<a href='MyProducts.php' class='list-group-item list-group-item-action'>My Books</a>";
                    echo "<a href='Transactions.php' class='list-group-item list-group-item-action'>My Transactions (Orders)</a>";
                    echo "<a href='bid_insert.php' class='dropdown-item'>Bid</a>";
                    echo "<a href='display_bids2.php' class='dropdown-item'>Bid Message</a>";
                    echo "<a href='online_class.php' class='dropdown-item'>Post Meet & Greet</a>"; // Updated text for consistency
                    echo "<a href='leaderboard.php' class='dropdown-item'>See Quiz Results</a>";
                    echo "<a href='donate_book.php' class='list-group-item list-group-item-action'>Donation</a>"; // Added missing donation link
                    echo "<a href='viewclaim.php' class='list-group-item list-group-item-action'>Donation Message</a>"; // Added missing donation message link
                    echo "<a href='logout.php' class='list-group-item list-group-item-action '>Logout</a>";
                } else {
                    echo "<a href='../auth/FarmerLogin.php' class='list-group-item list-group-item-action'>Login</a>";
                }
                ?>
            </div>
               
            <div class="d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px; margin-left: auto; margin-right: 150px;"> 
                
                <?php getFarmerUsername(); ?>

                <div class="dropdown">
                    <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        if (isset($_SESSION['phonenumber'])) {
                            echo "<a href='FarmerProfile2.php' class='dropdown-item'>Profile</a>"; // Updated link for consistency
                            echo "<a href='Transactions.php' class='dropdown-item'>Orders</a>";
                            echo "<a href='bid_insert.php' class='dropdown-item'>Post A Rare Book</a>";
                            echo "<a href='online_class.php' class='dropdown-item'>Post Rare Book Exhibition</a>";
                            echo "<a href='leaderboard.php' class='dropdown-item'>See Quiz Results</a>";
                            echo "<a href='donate_book.php' class='dropdown-item'>Donation</a>";
                            echo "<a href='../auth/FarmerLogin.php' class='dropdown-item'>Logout</a>";
                        } else {
                            echo "<a href='../auth/FarmerLogin.php' class='dropdown-item'>Login</a>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <div class="container main-nav-section">
        <div class="d-flex justify-content-center flex-wrap">
            <a href="farmerHomepage.php" class="main-nav-btn">
                <i class="fa fa-home" aria-hidden="true"></i>Home
            </a>
            <a href="MyProducts.php" class="main-nav-btn">
                <i class="fa fa-book" aria-hidden="true"></i>My Books
            </a>
            <a href="Transactions.php" class="main-nav-btn">
                <i class="fa fa-exchange" aria-hidden="true"></i>My Transactions
            </a>
            <a href="borrowlist.php" class="main-nav-btn">
                <i class="fa fa-list" aria-hidden="true"></i>Book Borrow List
            </a>
        </div>
    </div>
    <hr>


    <div class=content_item>
        <label><b>All Books</b></label>
        <?php
        include("../Includes/db.php");
        if (isset($_SESSION['phonenumber'])) {
            echo "<a href='InsertProduct.php'>
            <button class='btn btn-add-new p-3 m-3'>Add New Book <i class='fas fa-plus-square p-2 fa-1x'></i>
            </button>
            </a>";
        } else {
            echo "<a href='../auth/FarmerLogin.php'>
            <button class='btn btn-add-new p-3 m-3'>Add New Book <i class='fas fa-plus-square p-2 fa-1x'></i>
            </button>
            </a>";
        }
        ?>

    </div>

    <main>
        <div class="products">
            <?php
            // NOTE: db.php is included twice, which is fine, but redundant.
            include("../Includes/db.php"); 
            if (isset($_SESSION['phonenumber'])) {
                $sess_phone_number = $_SESSION['phonenumber'];
                getFarmerProducts();
            } else {
                echo "<br><br><h1 align = center>Please Login!</h1><br><br><hr>";
            }
            ?>
        </div>
        <br> <br>
        <hr>
    </main>

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
                    <p>Smart Library Management System - A one stop solution for users and librarians</p>
                    <p class="h6"><a class="text-green ml-2" target="_blank">Foreign Key Friends</a></p>
                </div>
                </hr>
            </div>
        </div>
    </section>

</body>

</html>