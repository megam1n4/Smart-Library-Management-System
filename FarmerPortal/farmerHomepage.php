<?php
     include("../Functions/functions.php");

     $user_count = getTotalUsers();
     $book_count = getTotalBooks();
     $rare_book_count = getTotalRareBooks();
     $borrowed_count = getTotalBorrowedBooks();
     // ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Author Homepage</title>
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

        /* Modern Navbar Styling - Using Farmer's existing green/dark scheme */
        nav.navbar {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        /* ADDED: Logo styling from bhome.php */
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
        /* END ADDED Logo styling */


        .navbar-brand h1 {
            color: goldenrod !important;
            font-weight: 800;
            font-size: 28px;
            margin: 0;
        }

        /* Search Box Styling (Removed for Author Portal as it only has a "My Books" section) 
           If you want a search bar for the author's own books, uncomment and adapt this:
        .searchbox .input-group {
            max-width: 600px;
            margin: 0 auto;
        } */


        /* User Icons */
        .user-icon,
        .cart-icon {
            color: goldenrod;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-icon:hover,
        .cart-icon:hover {
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
        
        /* Hero Section - Adapted for Author Portal */
        .hero-section {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            padding: 80px 0;
            text-align: center;
            color: white;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('splash.jpg') center/cover;
            opacity: 0.2;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: goldenrod;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        /* Category Buttons (Replaced with Main Navigation) */
        .main-nav-section {
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
        }

        .main-nav-btn:hover {
            background: #28a745;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            text-decoration: none;
        }
        
        .main-nav-btn i {
            margin-right: 8px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin: 60px 0 40px 0;
            position: relative;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a2e;
            display: inline-block;
            padding: 0 30px;
            background: #f8f9fa;
            position: relative;
            z-index: 1;
        }

        .section-header::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, goldenrod, transparent);
            z-index: 0;
        }

        /* === NEW: DASHBOARD STYLES === */
        .dashboard-section {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 30px 25px;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .dashboard-card i {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .dashboard-card-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1.2;
        }

        .dashboard-card-label {
            font-size: 1.1rem;
            color: #555;
            font-weight: 600;
        }

        /* Icon Colors for Dashboard */
        .card-users i { color: #007bff; }
        .card-books i { color: #28a745; }
        .card-rare i { color: #dc3545; }
        .card-borrowed i { color: #ffc107; }
        /* === END: DASHBOARD STYLES === */


        /* Feature Cards */
        .card-deck .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin: 15px;
            flex: 1 1 300px; /* For responsive sizing */
        }

        .card-deck .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .card-deck .card img {
            height: 180px;
            object-fit: contain;
            padding: 20px;
        }

        .card-deck .card-body h4 {
            color: #28a745;
            font-weight: 700;
        }
        
        .card-deck .card-body h5 {
            color: #666;
            font-size: 1rem;
        }


        /* Footer */
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .section-header h2 {
                font-size: 1.8rem;
            }

            .main-nav-btn {
                width: 100%;
                margin: 5px 0;
            }

            .card-deck {
                flex-direction: column;
                align-items: center;
            }
            .card-deck .card {
                width: 90%;
                margin: 15px 0;
            }
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
                
                <?php getFarmerUsername(); ?>

                <div class="list-group moblists">
                    <?php
                        if (isset($_SESSION['phonenumber'])) {
                            echo "<a href='FarmerProfile2.php' class='list-group-item list-group-item-action'>Profile</a>";
                            echo "<a href='MyProducts.php' class='list-group-item list-group-item-action'>My Books</a>";
                            echo "<a href='Transactions.php' class='list-group-item list-group-item-action'>My Transactions</a>";
                            echo "<a href='bid_insert.php' class='list-group-item list-group-item-action'>Bid</a>";
                            echo "<a href='display_bids2.php' class='list-group-item list-group-item-action'>Bid Message</a>";
                            echo "<a href='online_class.php' class='list-group-item list-group-item-action'>Post Meet & Greet</a>";
                            echo "<a href='leaderboard.php' class='list-group-item list-group-item-action'>See Quiz Results</a>";
                            echo "<a href='donate_book.php' class='list-group-item list-group-item-action'>Donation</a>";
                            echo "<a href='viewclaim.php' class='list-group-item list-group-item-action'>Donation Message</a>";
                            echo "<a href='logout.php' class='list-group-item list-group-item-action'>Logout</a>";
                        } else {
                            echo "<a href='../auth/FarmerLogin.php' class='list-group-item list-group-item-action'>Login</a>";
                        }
                    ?>
                </div>

                <div style="display: flex; align-items: center; gap: 20px; margin-left: auto; margin-right: 150px;"> 
                    
                    <div class="dropdown">
                        <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            More
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            if (isset($_SESSION['phonenumber'])) {
                                echo "<a href='FarmerProfile2.php' class='dropdown-item'>Profile</a>";
                                echo "<a href='Transactions.php' class='dropdown-item'>Orders</a>";
                                echo "<a href='bid_insert.php' class='dropdown-item'>Post A Rare Book</a>";
                                echo "<a href='online_class.php' class='dropdown-item'>Post Rare Book Exhibition</a>";
                                echo "<a href='leaderboard.php' class='dropdown-item'>See Quiz Results</a>";
                                echo "<a href='donate_book.php' class='dropdown-item'>Donation</a>";
                                echo "<a href='reservelist.php' class='dropdown-item'>Rare Book Reservation List</a>";
                                echo "<a href='../auth/FarmerLogin.php' class='dropdown-item'>Logout</a>";
                            } else {
                                echo "<a href='../auth/FarmerLogin.php' class='dropdown-item'>Login</a>";
                            }
                            ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="hero-content container">
            <h1>Librarian Portal</h1>
            <p>Empowering Librarians in the Smart Library Management System</p>
        </div>
    </div>

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


    <div class="container dashboard-section">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-users">
                    <i class="fas fa-users"></i>
                    <div class="dashboard-card-number"><?php echo $user_count; ?></div>
                    <div class="dashboard-card-label">Total Users</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-books">
                    <i class="fas fa-book-open"></i>
                    <div class="dashboard-card-number"><?php echo $book_count; ?></div>
                    <div class="dashboard-card-label">Total Books</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-rare">
                    <i class="fas fa-gem"></i>
                    <div class="dashboard-card-number"><?php echo $rare_book_count; ?></div>
                    <div class="dashboard-card-label">Rare Books</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card card-borrowed">
                    <i class="fas fa-handshake"></i>
                    <div class="dashboard-card-number"><?php echo $borrowed_count; ?></div>
                    <div class="dashboard-card-label">Borrowed Books</div>
                </div>
            </div>
        </div>
    </div>
    <div class="features container">
        <div class="section-header">
            <h2>✨ Standout Features</h2>
        </div>
        <div class="card-deck row text-center" style="display: flex; flex-wrap: wrap;">
            <div class="card">
                <p class="aligncenter">
                    <img class="card-img-top" src="../Images/Homepage/sms.png" alt="Chatting System" >
                </p>
                <div class="card-body">
                    <h4 class="card-title">Chatting System</h4>
                    <h5 class="card-text">Upload and Ask About Your Books in the Chatting System</h5>
                </div>
            </div>
            
            <div class="card">
                <p class="aligncenter">
                    <img class="card-img-top" src="../Images/Homepage/handshake.png" alt="Reader Connection" >
                </p>
                <div class="card-body">
                    <h4 class="card-title">Reader Connection</h4>
                    <h5 class="card-text">Get in direct touch with the reader to satisfy its need</h5>
                </div>
            </div>
            
            <div class="card">
                <p class="aligncenter">
                    <img class="card-img-top" src="../Images/Homepage/farmer.png" alt="Author Group Formation" >
                </p>
                <div class="card-body">
                    <h4 class="card-title">Librarian Community</h4>
                    <h5 class="card-text">Connect with other librarians, form a community, and ask for help</h5>
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

</body>

</html>