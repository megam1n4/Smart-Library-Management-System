<?php
include("../Functions/functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Genres - Smart Library</title>
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

        /* Modern Navbar Styling (same as other pages) */
        nav.navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            border-radius: 12px;
            background-color: white;
            padding: 5px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .searchbox .input-group {
            max-width: 600px;
            margin: 0 auto;
        }

        .searchbox .input-group-text {
            background-color: white;
            border: 2px solid #28a745;
            border-right: none;
            border-radius: 50px 0 0 50px;
            padding: 10px 15px;
            color: #28a745;
        }

        .searchbox .form-control {
            border: 2px solid #28a745;
            border-left: none;
            border-radius: 0 50px 50px 0;
            padding: 10px 20px;
            box-shadow: none;
        }

        .searchbox .form-control:focus {
            box-shadow: 0 0 0 0.1rem rgba(40, 167, 69, 0.25);
        }

        .user-icon,
        .cart-icon {
            color: #ffc107;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-icon:hover,
        .cart-icon:hover {
            color: #20c997;
            transform: scale(1.1);
        }

        #icon {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .voice-search-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(32, 201, 151, 0.4);
            transition: all 0.3s ease;
        }

        .voice-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(32, 201, 151, 0.6);
            color: #fff !important;
        }

        .dropdown-menu {
            border-radius: 10px;
            padding: 10px 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .dropdown-menu .dropdown-item {
            padding: 8px 20px;
            font-weight: 500;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #28a745;
            color: #fff;
        }

        .btn-success.explore-btn,
        .btn.btn-success.dropdown-toggle {
            background: #28a745;
            border-radius: 50px;
            border: none;
            padding: 8px 22px;
            font-weight: 600;
        }

        .btn-success.explore-btn:hover,
        .btn.btn-success.dropdown-toggle:hover {
            background: #20c997;
        }

        /* Mobile nav list */
        .moblists {
            display: none;
        }

        @media (max-width: 1199.98px) {
            .searchbox {
                margin-top: 15px;
            }

            .moblists {
                display: block;
                margin-top: 15px;
            }

            .moblists .list-group-item {
                background-color: #1a1a2e !important;
                color: #ffc107 !important;
                border: none;
                text-align: center;
                padding: 15px;
                transition: all 0.3s ease;
            }

            .moblists .list-group-item:hover {
                background-color: #28a745 !important;
                color: white !important;
            }
        }

        /* Quiz hero section (new layout) */
        .quiz-hero {
            position: relative;
            padding: 120px 15px;
            margin-top: 20px;
        }

        .quiz-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('test3.jpg') center center / cover no-repeat fixed;
            filter: brightness(0.7);
            z-index: 0;
        }

        .quiz-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.85), rgba(118, 75, 162, 0.85));
            z-index: 1;
        }

        .quiz-hero-inner {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.55);
            border-radius: 20px;
            padding: 50px 30px 55px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            color: #fff;
            animation: fadeIn 0.9s ease-out;
        }

        .quiz-hero-inner h1 {
            font-size: 2.7rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .quiz-hero-inner p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .genre-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .genre-button {
            min-width: 150px;
            padding: 12px 28px;
            border-radius: 999px;
            border: none;
            background: #28a745;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.45);
            transition: all 0.25s ease;
        }

        .genre-button:nth-child(2) {
            background: #17a2b8;
            box-shadow: 0 10px 25px rgba(23, 162, 184, 0.45);
        }

        .genre-button:nth-child(3) {
            background: #ffc107;
            color: #1a1a2e;
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.45);
        }

        .genre-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.6);
        }

        .genre-helper-text {
            margin-top: 18px;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* Footer (same style as other pages) */
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

        .social li {
            margin: 0 10px;
        }

        .social a {
            color: #ffc107;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .social a:hover {
            color: #28a745;
            transform: scale(1.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Navbar (copied from existing pages) -->
    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="bhome.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>

            <!-- Mobile Icons -->
            <div class="d-xl-none" style="display: flex; align-items: center; gap: 15px;">
                <i class='far fa-user-circle user-icon'></i>
                <a href="CartPage.php" style="position: relative;">
                    <i class="fa fa-shopping-cart cart-icon"></i>
                    <span id="icon"><?php echo totalItems(); ?></span>
                </a>
            </div>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-bars" style="color:#28a745; font-size:28px;"></i>
            </button>

            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Search Box -->
                <div class="mx-auto searchbox">
                    <form action="SearchResult.php" method="get" enctype="multipart/form-data">
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search" style="font-size:20px;"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="searchItem" placeholder="Search for education, romance books" style="width:500px;">
                        </div>
                    </form>
                </div>

                <!-- Username Display -->
                <?php getUsername(); ?>

                <!-- Mobile Menu List -->
                <div class="list-group moblists">
                    <?php
                    if (isset($_SESSION['phonenumber'])) {
                        echo "<a href='BuyerProfile.php' class='list-group-item list-group-item-action'>Profile</a>";
                        echo "<a href='Transaction.php' class='list-group-item list-group-item-action'>Transactions</a>";
                        echo "<a href='claimbook.php' class='list-group-item list-group-item-action'>Claim Book</a>";
                        echo "<a href='display.php' class='list-group-item list-group-item-action'>Bid Rare Book</a>";
                        echo "<a href='chat.php' class='list-group-item list-group-item-action'>Group Chat</a>";
                        echo "<a href='debate.php' class='list-group-item list-group-item-action'>Join Debate</a>";
                        echo "<a href='genre.php' class='list-group-item list-group-item-action'>Join Quiz</a>";
                        echo "<a href='customersupport.php' class='list-group-item list-group-item-action'>Join Meet &amp; Greet</a>";
                        echo "<a href='../Includes/logout.php' class='list-group-item list-group-item-action'>Logout</a>";
                    } else {
                        echo "<a href='../auth/UserLogin.php' class='list-group-item list-group-item-action'>Login</a>";
                    }
                    ?>
                </div>

                <!-- Desktop Right Icons -->
                <div class="ml-auto d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px;">
                    <!-- Voice Search -->
                    <a href="voice_search.php" class="voice-search-btn">
                        <i class="fas fa-microphone"></i> Voice Search
                    </a>

                    <!-- Explore Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle explore-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Explore
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            if (isset($_SESSION['phonenumber'])) {
                                echo "<a href='UserProfile.php' class='dropdown-item'>Profile</a>";
                                
                                echo "<a href='display.php' class='dropdown-item'>Reserve Rare Book</a>";
                                echo "<a href='chat.php' class='dropdown-item'>Group chat</a>";
                                
                                echo "<a href='genre.php' class='dropdown-item'>Join Quiz</a>";
                                echo "<a href='Donate.php' class='dropdown-item'>Book Donation</a>";
                                echo "<a href='exhibition.php' class='dropdown-item'>Join Rare Book Exhibition</a>";
                                echo "<a href='../Includes/logout.php' class='dropdown-item'>Logout</a>";
                            } else {
                                echo "<a href='../auth/UserLogin.php' class='dropdown-item'>Login</a>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- User Icon -->
                    <i class='far fa-user-circle user-icon'></i>

                    <!-- Cart Icon -->
                    <a href="CartPage.php" style="position: relative;">
                        <i class="fa fa-shopping-cart cart-icon"></i>
                        <span id="icon"><?php echo totalItems(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Quiz Genre Hero (new look) -->
    <section class="quiz-hero">
        <div class="quiz-hero-inner">
            <h1>Smart Library Management System</h1>
            <p>Choose your favorite genre for Quiz</p>

            <div class="genre-buttons">
                <!-- Backend logic kept same: all buttons go to quiz.php -->
                <button class="genre-button" onclick="window.location.href='quiz.php'">Literature</button>
                <button class="genre-button" onclick="window.location.href='quiz2.php'">SQL Coding</button>
                <button class="genre-button" onclick="window.location.href='quiz.php'">Music</button>
            </div>

            <div class="genre-helper-text">
                Select any genre to start — questions will be loaded from the quiz engine.
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section id="footer" class="myfooter">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <ul class="list-unstyled list-inline social text-center">
                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="fab fa-google-plus"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2 text-center">
                    <p><strong>Smart Library Management System</strong> - An Advanced Digital Library Management System</p>
                    <p>Copyright &copy; All Rights Reserved. Foreign Key Friends</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
