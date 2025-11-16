<?php
include("../Functions/functions.php");
include("../Includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('You must be logged in to reserve books.');</script>";
    echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
    exit();
}

$buyer_phone = $_SESSION['phonenumber'];

// Handle Reserve button submission
if (isset($_POST['reserve_book'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($con, $_POST['product_description']);
    $product_image = mysqli_real_escape_string($con, $_POST['product_image']);
    
    // Get buyer information from session
    $buyer_address = $buyer_phone; // Using phone as buyer address
    
    // Insert reservation into bids table (matching the actual table structure)
    $query = "INSERT INTO bids (product_id, product_name, product_description, product_image, farmer_phone, buyer_address) 
              VALUES ('$product_id', '$product_name', '$product_description', '$product_image', '', '$buyer_address')";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $message = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Book reserved successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> Failed to reserve the book. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rare Books - Smart Library</title>

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

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 50px;
        }

        .table-container h2 {
            color: #1a1a2e;
            font-weight: 800;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2rem;
        }

        /* Modern Table Styling */
        .rare-books-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        .rare-books-table thead th {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 18px 15px;
            font-weight: 700;
            text-align: center;
            font-size: 1.1rem;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rare-books-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .rare-books-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .rare-books-table tbody tr {
            background: #f8f9fa;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .rare-books-table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .rare-books-table tbody td {
            padding: 20px 15px;
            text-align: center;
            vertical-align: middle;
            border: none;
            color: #333;
        }

        .rare-books-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .rare-books-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Book Name Styling */
        .book-name {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 1.1rem;
        }

        /* Book Description */
        .book-description {
            color: #666;
            line-height: 1.5;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Book Image */
        .book-image {
            width: 120px;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .book-image:hover {
            transform: scale(1.05);
        }

        /* Placeholder for missing images */
        .book-image-placeholder {
            width: 120px;
            height: 160px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
        }

        /* Reserve Button */
        .btn-reserve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reserve:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-reserve i {
            margin-right: 5px;
        }

        /* Alert Messages */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* No Books Message */
        .no-books-message {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-books-message i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .no-books-message h3 {
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 10px;
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

            .table-container {
                padding: 20px;
                overflow-x: auto;
            }

            .rare-books-table {
                font-size: 0.9rem;
            }

            .book-image {
                width: 80px;
                height: 110px;
            }

            .book-description {
                font-size: 0.85rem;
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

    <!-- Page Header -->
    <div class="page-header">
        <h1>📚 Rare Book Collection</h1>
        <p>Discover and reserve exclusive rare books</p>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="table-container">
            <h2>Available Rare Books</h2>

            <!-- Display message -->
            <?php if (isset($message)) echo $message; ?>

            <!-- Rare Books Table -->
            <div class="table-responsive">
                <table class="rare-books-table">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Description</th>
                            <th>Book Image</th>
                            <th>Reserve</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to fetch data from the 'bid' table
                        $query = "SELECT product_id, product_name, product_description, product_image FROM bid";
                        $result = mysqli_query($con, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $image_html = '';
                                
                                // Check if product_image is blob data or file path
                                if (!empty($row['product_image'])) {
                                    // If it's blob data (binary), convert to base64
                                    if (is_string($row['product_image']) && strpos($row['product_image'], '/') === false && strpos($row['product_image'], '\\') === false) {
                                        // Likely blob data - convert to base64
                                        $image_data = base64_encode($row['product_image']);
                                        $image_html = "<img src='data:image/jpeg;base64," . $image_data . "' alt='Book Cover' class='book-image'>";
                                    } else {
                                        // It's a file path
                                        $image_path = htmlspecialchars($row['product_image']);
                                        if (file_exists($image_path)) {
                                            $image_html = "<img src='" . $image_path . "' alt='Book Cover' class='book-image'>";
                                        } else {
                                            // File doesn't exist, show placeholder
                                            $image_html = "<div class='book-image-placeholder'><i class='fas fa-book'></i></div>";
                                        }
                                    }
                                } else {
                                    // No image data, show placeholder
                                    $image_html = "<div class='book-image-placeholder'><i class='fas fa-book'></i></div>";
                                }
                                
                                // Store image reference (could be blob or path)
                                $image_value = !empty($row['product_image']) ? base64_encode($row['product_image']) : '';
                                
                                echo "<tr>
                                    <td class='book-name'>" . htmlspecialchars($row['product_name']) . "</td>
                                    <td class='book-description'>" . htmlspecialchars($row['product_description']) . "</td>
                                    <td>" . $image_html . "</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='product_id' value='" . htmlspecialchars($row['product_id']) . "'>
                                            <input type='hidden' name='product_name' value='" . htmlspecialchars($row['product_name']) . "'>
                                            <input type='hidden' name='product_description' value='" . htmlspecialchars($row['product_description']) . "'>
                                            <input type='hidden' name='product_image' value='" . $image_value . "'>
                                            <button type='submit' name='reserve_book' class='btn-reserve'>
                                                <i class='fas fa-bookmark'></i> Reserve
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='no-books-message'>
                                    <i class='fas fa-book-open'></i>
                                    <h3>No Rare Books Available</h3>
                                    <p>Check back soon for new additions to our collection!</p>
                                  </td></tr>";
                        }

                        mysqli_close($con);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
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