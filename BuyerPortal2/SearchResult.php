<?php
include("../Functions/functions.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Smart Library</title>
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

        /* Modern Navbar Styling - Same as bhome.php */
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
        }

        .voice-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        /* Search Results Header */
        .search-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .search-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .search-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Category Buttons */
        .category-section {
            margin-bottom: 40px;
        }

        .category-btn {
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

        .category-btn:hover {
            background: #28a745;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        /* Book Cards */
        .book-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .book-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .book-card .card-body {
            padding: 15px 0;
        }

        .book-card h5 {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .book-card .badge {
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 15px;
        }

        .book-card .btn-add-cart {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .book-card .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        /* No Results Message */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin: 40px 0;
        }

        .no-results i {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .no-results h2 {
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .no-results p {
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

        /* Responsive */
        @media (max-width: 768px) {
            .search-header h1 {
                font-size: 2rem;
            }

            .search-header p {
                font-size: 1rem;
            }

            .category-btn {
                width: 100%;
                margin: 5px 0;
            }

            .book-card img {
                height: 250px;
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
                        echo "<a href='BuyerProfile.php' class='list-group-item list-group-item-action'>Profile</a>";
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
                                echo "<a href='debate.php' class='dropdown-item'>Join Debate</a>";
                                echo "<a href='genre.php' class='dropdown-item'>Join Quiz</a>";
                                
                                echo "<a href='customersupport.php' class='dropdown-item'>Join Rare Book Exhibition</a>";
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

    <!-- Category Buttons -->
    <div class="container category-section" style="margin-top: 30px;">
        <div class="d-flex justify-content-center flex-wrap">
            <div class="dropdown">
                <button class="category-btn dropdown-toggle" type="button" data-toggle="dropdown">
                    📚 Romantic
                </button>
                <div class="dropdown-menu">
                    <?php getFruits(); ?>
                </div>
            </div>

            <div class="dropdown">
                <button class="category-btn dropdown-toggle" type="button" data-toggle="dropdown">
                    💼 Business
                </button>
                <div class="dropdown-menu">
                    <?php getVegetables(); ?>
                </div>
            </div>

            <div class="dropdown">
                <button class="category-btn dropdown-toggle" type="button" data-toggle="dropdown">
                    🎓 Educational
                </button>
                <div class="dropdown-menu">
                    <?php getCrops(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Section -->
    <div class="container">
        <?php
        cart();
        
        if (isset($_GET['search'])) {
            // Get the raw search query
            $search_query = $_GET['search'];

            // Define an array of common phrases to ignore
            $ignore_phrases = [
                "search", "find", "give me", "I want", "show me", "book", "books"
            ];

            // Convert the search query to lowercase for consistent matching
            $search_query = strtolower($search_query);

            // Remove the ignore phrases from the search query
            foreach ($ignore_phrases as $phrase) {
                $search_query = str_replace($phrase, "", $search_query);
            }

            // Trim any remaining whitespace
            $search_query = trim($search_query);

            // Display search header
            echo "
            <div class='search-header'>
                <h1>🔍 Search Results</h1>
                <p>Showing results for: <strong>" . htmlspecialchars($_GET['search']) . "</strong></p>
            </div>
            ";

            $get_pro = "SELECT * FROM products WHERE product_title LIKE '%$search_query%' OR product_type LIKE '%$search_query%' OR product_keywords LIKE '%$search_query%'";

            $run_pro = mysqli_query($con, $get_pro);
            $count = mysqli_num_rows($run_pro);
            
            if ($count > 0) {
                echo "<div class='row'>";
                
                while ($rows = mysqli_fetch_array($run_pro)) {
                    $product_id = $rows['product_id'];
                    $product_title = $rows['product_title'];
                    $product_image = $rows['product_image'];
                    $product_type = $rows['product_type'];

                    echo "
                    <div class='col-md-4 col-sm-6 mb-4'>
                        <div class='book-card'>
                            <a href='../BuyerPortal2/ProductDetails.php?id=$product_id'>
                                <img src='../Admin/product_images/$product_image' alt='$product_title' class='img-fluid'>
                            </a>
                            
                            <div class='card-body'>
                                <h5>$product_title</h5>
                                <span class='badge'>$product_type</span>
                                
                                <form action='' method='post'>
                                    <input type='hidden' name='product_id' value='$product_id'>
                                    <button type='submit' name='cart' class='btn-add-cart'>
                                        <i class='fas fa-shopping-cart'></i> Add to cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    ";

                    // Handle add to cart
                    if (isset($_POST['cart'])) {
                        $qty = 1;
                        if (isset($_SESSION['phonenumber'])) {
                            $sess_phone_number = $_SESSION['phonenumber'];
                            $check_pro = "select * from cart where phonenumber = $sess_phone_number and product_id='$product_id'";
                            $run_check = mysqli_query($con, $check_pro);

                            if (mysqli_num_rows($run_check) > 0) {
                                echo "";
                            } else {
                                $insert_pro = "insert into cart (product_id,phonenumber) values ('$product_id','$sess_phone_number')";
                                $run_insert_pro = mysqli_query($con, $insert_pro);
                                echo "<script>window.location.reload(true)</script>";
                            }
                        } else {
                            echo "<script>window.alert('Please Login First!');</script>";
                        }
                    }
                }
                
                echo "</div>";
            } else {
                echo "
                <div class='no-results'>
                    <i class='fas fa-search'></i>
                    <h2>No Books Found</h2>
                    <p>We couldn't find any books matching your search: <strong>" . htmlspecialchars($_GET['search']) . "</strong></p>
                    <p>Try searching with different keywords or browse our categories above.</p>
                </div>
                ";
            }
        }
        ?>
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