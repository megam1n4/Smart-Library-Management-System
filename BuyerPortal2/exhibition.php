<?php
include("../Functions/functions.php");
include("../Includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('You must be logged in to view available meet-and-greet sessions.');</script>";
    echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Get the phone number of the logged-in reader

// Fetch reader information (e.g., name) based on phone number
$query = "SELECT buyer_name FROM buyerregistration WHERE buyer_phone = '$phonenumber'";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) > 0) {
    $reader = mysqli_fetch_assoc($result);
    $reader_name = $reader['buyer_name'];
} else {
    echo "No reader found with this phone number.";
    exit();
}

// Fetch upcoming meet-and-greet sessions from the database
$meet_and_greet_query = "SELECT mg.title, mg.description, mg.meet_link, mg.meet_date, mg.meet_time, f.farmer_name 
                         FROM meet_and_greet mg
                         JOIN farmerregistration f ON mg.farmer_id = f.farmer_id
                         WHERE mg.meet_date >= CURDATE()
                         ORDER BY mg.meet_date, mg.meet_time";
$meet_and_greet_result = mysqli_query($con, $meet_and_greet_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet and Greet Sessions - Smart Library</title>

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

        /* Modern Navbar Styling */
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
            object-fit: contain;
            background: white;
            padding: 5px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        /* Search Box Styling */
        .searchbox .input-group {
            max-width: 600px;
            margin: 0 auto;
        }

        .searchbox .input-group-text {
            background-color: white;
            border: 2px solid #28a745;
            border-right: none;
            border-radius: 25px 0 0 25px;
            color: #28a745;
        }

        .searchbox .form-control {
            border: 2px solid #28a745;
            border-left: none;
            border-radius: 0 25px 25px 0;
            padding: 10px 20px;
        }

        .searchbox .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            border-color: #28a745;
        }

        /* User Icons */
        .user-icon,
        .cart-icon {
            color: #28a745;
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

        /* Dropdown Button */
        .btn-success {
            background: #28a745;
            border: none;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Voice Search Button */
        .voice-search-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .voice-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Welcome Alert */
        .welcome-alert {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
        }

        .welcome-alert i {
            margin-right: 10px;
            font-size: 1.3rem;
        }

        /* Session Cards */
        .session-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .session-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .session-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .session-card-header h5 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .session-info {
            margin-bottom: 15px;
        }

        .session-info p {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #555;
        }

        .session-info strong {
            color: #1a1a2e;
            font-weight: 700;
        }

        .host-name {
            color: #28a745;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .host-name i {
            font-size: 1.2rem;
        }

        .session-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .session-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1a1a2e;
            font-weight: 600;
        }

        .meta-item i {
            color: #667eea;
            font-size: 1.1rem;
        }

        .btn-join {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-join:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-join i {
            margin-right: 8px;
        }

        /* No Sessions Alert */
        .no-sessions-alert {
            background: white;
            border-radius: 15px;
            padding: 60px 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .no-sessions-alert i {
            font-size: 5rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .no-sessions-alert h3 {
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .no-sessions-alert p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Footer */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: 80px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .session-card {
                padding: 20px;
            }

            .session-meta {
                flex-direction: column;
                gap: 10px;
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
    </style>
</head>

<body>

    <!-- Modern Navbar -->
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
                            <input type="text" class="form-control" name="search" placeholder="Search for education, romance books" style="width:500px;">
                        </div>
                    </form>
                </div>

                <!-- Username Display -->
                <?php getUsername(); ?>

                <!-- Mobile Menu List -->
                <div class="list-group moblists">
                    <?php
                    if (isset($_SESSION['phonenumber'])) {
                        echo "<a href='UserProfile.php' class='list-group-item list-group-item-action'>Profile</a>";
                        echo "<a href='Transaction.php' class='list-group-item list-group-item-action'>Transactions</a>";
                        echo "<a href='claimbook.php' class='list-group-item list-group-item-action'>Claim Book</a>";
                        echo "<a href='display.php' class='list-group-item list-group-item-action'>Bid Rare Book</a>";
                        echo "<a href='chat.php' class='list-group-item list-group-item-action'>Group Chat</a>";
                        echo "<a href='debate.php' class='list-group-item list-group-item-action'>Join Debate</a>";
                        echo "<a href='genre.php' class='list-group-item list-group-item-action'>Join Quiz</a>";
                        echo "<a href='customersupport.php' class='list-group-item list-group-item-action'>Join Meet & Greet</a>";
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
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
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

    <!-- Page Header -->
    <div class="page-header">
        <h1>🤝 Rare Book Exhibition</h1>
        <p>Join and explore the books you never seen before</p>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome-alert">
            <i class="fas fa-user-circle"></i>
            Welcome, <?php echo htmlspecialchars($reader_name); ?>! Here are the upcoming rare book exhibition sessions.
        </div>

        <?php if (mysqli_num_rows($meet_and_greet_result) > 0): ?>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($meet_and_greet_result)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="session-card">
                            <div class="session-card-header">
                                <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                            </div>

                            <div class="host-name">
                                <i class="fas fa-user-tie"></i>
                                Hosted by: <?php echo htmlspecialchars($row['farmer_name']); ?>
                            </div>

                            <div class="session-description">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </div>

                            <div class="session-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><?php echo date("F j, Y", strtotime($row['meet_date'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo date("g:i A", strtotime($row['meet_time'])); ?></span>
                                </div>
                            </div>

                            <a href="<?php echo htmlspecialchars($row['meet_link']); ?>" target="_blank" class="btn-join">
                                <i class="fas fa-video"></i> Join Meeting
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-sessions-alert">
                <i class="fas fa-calendar-times"></i>
                <h3>No Upcoming Sessions</h3>
                <p>No rare book exhibition sessions are available at the moment. Please check back later!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <section id="footer" class="myfooter">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <ul class="list-unstyled list-inline social text-center">
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-google-plus"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2 text-center">
                    <p><strong>Smart Library Management System</strong> - An Advanced Digital Library Management System</p>
                    <p>Copyright © All Rights Reserved. Foreign Key Friends</p>
                </div>
            </div>
        </div>
    </section>

</body>

</html>