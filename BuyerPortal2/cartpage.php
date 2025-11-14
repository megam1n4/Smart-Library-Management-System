<?php
include("../Functions/functions.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Smart Library</title>

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

        /* Cart Header */
        .cart-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .cart-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .cart-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Cart Table */
        .cart-table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            font-weight: 700;
            border: none;
            padding: 15px;
            text-align: center;
            font-size: 1rem;
        }

        .table tbody td {
            padding: 20px 15px;
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
            font-size: 1rem;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Quantity Controls */
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .qty-btn {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .qty-input {
            width: 60px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 8px;
            font-weight: 600;
        }

        /* Date Picker */
        .date-input {
            width: 100%;
            max-width: 150px;
            padding: 8px 12px;
            border: 2px solid #28a745;
            border-radius: 8px;
            font-size: 0.9rem;
            text-align: center;
            margin: 0 auto;
            display: block;
        }

        .date-input:focus {
            outline: none;
            border-color: #20c997;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        /* Delete Button */
        .delete-btn {
            color: #dc3545;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            color: #c82333;
            transform: scale(1.2);
        }

        /* Action Buttons */
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .action-btn {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #000;
            border: none;
            padding: 15px 35px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.4);
        }

        .checkout-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .checkout-btn:hover {
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        /* Empty Cart Message */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin: 40px 0;
        }

        .empty-cart i {
            font-size: 80px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .empty-cart h2 {
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 15px;
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
            .cart-header h1 {
                font-size: 2rem;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                padding: 15px;
            }

            .table tbody td {
                display: block;
                text-align: left;
                padding: 10px 0;
                border: none;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                display: inline-block;
                width: 120px;
                color: #1a1a2e;
            }

            .qty-control {
                justify-content: flex-start;
                margin-left: 120px;
            }

            .date-input {
                margin: 0;
            }

            .cart-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
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
                    <a href="voice_search.php" class="voice-search-btn" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
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
                                echo "<a href='BuyerProfile2.php' class='dropdown-item'>Profile</a>";
                                echo "<a href='Transaction.php' class='dropdown-item'>Transactions</a>";
                                echo "<a href='display.php' class='dropdown-item'>Bid Rare Book</a>";
                                echo "<a href='chat.php' class='dropdown-item'>Group chat</a>";
                                echo "<a href='debate.php' class='dropdown-item'>Join Debate</a>";
                                echo "<a href='genre.php' class='dropdown-item'>Join Quiz</a>";
                                echo "<a href='claimbook.php' class='dropdown-item'>Claim Book</a>";
                                echo "<a href='customersupport.php' class='dropdown-item'>Join Meet & Greet</a>";
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

    <!-- Cart Header -->
    <?php
    // Handle Submit Borrow Request
    if (isset($_POST['submit_borrow_request'])) {
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];
            $success = true;
            $error_message = "";
            
            // Get all cart items
            $sel_cart = "select * from cart where phonenumber = '$sess_phone_number'";
            $run_cart = mysqli_query($con, $sel_cart);
            
            while ($cart_item = mysqli_fetch_array($run_cart)) {
                $product_id = $cart_item['product_id'];
                
                // Get borrow and return dates from POST
                $borrow_date_field = 'borrow_date_' . $product_id;
                $return_date_field = 'return_date_' . $product_id;
                
                if (isset($_POST[$borrow_date_field]) && isset($_POST[$return_date_field])) {
                    $borrow_date = mysqli_real_escape_string($con, $_POST[$borrow_date_field]);
                    $return_date = mysqli_real_escape_string($con, $_POST[$return_date_field]);
                    
                    // Validate dates
                    if (!empty($borrow_date) && !empty($return_date)) {
                        if (strtotime($return_date) >= strtotime($borrow_date)) {
                            // Update cart with borrow and return dates
                            $update_query = "UPDATE cart SET borrow_date = '$borrow_date', return_date = '$return_date' WHERE product_id = '$product_id' AND phonenumber = '$sess_phone_number'";
                            
                            if (!mysqli_query($con, $update_query)) {
                                $success = false;
                                $error_message = "Failed to update borrow dates for some items.";
                                break;
                            }
                        } else {
                            $success = false;
                            $error_message = "Return date must be on or after borrow date.";
                            break;
                        }
                    } else {
                        $success = false;
                        $error_message = "Please select both borrow and return dates for all books.";
                        break;
                    }
                } else {
                    $success = false;
                    $error_message = "Please select dates for all books in your cart.";
                    break;
                }
            }
            
            if ($success) {
                echo "<script>alert('Borrow request submitted successfully!');</script>";
                echo "<script>window.open('Checkout.php','_self')</script>";
            } else {
                echo "<script>alert('$error_message');</script>";
            }
        }
    }
    
    if (isset($_SESSION['phonenumber'])) {
        $temp = totalItems();
        echo "
        <div class='cart-header'>
            <h1>🛒 My Cart</h1>
            <p>You have $temp book(s) in your cart</p>
        </div>
        ";
    }
    ?>

    <div class="container">
        <?php
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];
            $sel_price = "select * from cart where phonenumber = '$sess_phone_number'";
            $run_price = mysqli_query($con, $sel_price);
            $count = mysqli_num_rows($run_price);

            if ($count > 0) {
        ?>
                <form method="POST" action="">
                    <div class="cart-table-container">
                        <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Book Name</th>
                                <th>Quantity</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $qtycart = array();
                            
                            while ($p_price = mysqli_fetch_array($run_price)) {
                                $product_id = $p_price['product_id'];
                                $_SESSION['qtycart'][$i] = $p_price['qty'];

                                $pro_price = "select * from products where product_id='$product_id'";
                                $run_pro_price = mysqli_query($con, $pro_price);
                                
                                while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                                    $product_title = $pp_price['product_title'];
                            ?>
                                    <tr>
                                        <td data-label="S.No"><?php echo $i + 1; ?></td>
                                        <td data-label="Book Name"><?php echo $product_title; ?></td>
                                        <td data-label="Quantity">
                                            <div class="qty-control">
                                                <a href="MinusQty.php?id=<?php echo $product_id; ?>">
                                                    <button class="qty-btn">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </a>
                                                <input type="number" class="qty-input" value="<?php echo $_SESSION['qtycart'][$i]; ?>" readonly>
                                                <a href="AddQty.php?id=<?php echo $product_id; ?>">
                                                    <button class="qty-btn">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                        <td data-label="Borrow Date">
                                            <input type="date" class="date-input" name="borrow_date_<?php echo $product_id; ?>" id="borrow_date_<?php echo $product_id; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                        </td>
                                        <td data-label="Return Date">
                                            <input type="date" class="date-input" name="return_date_<?php echo $product_id; ?>" id="return_date_<?php echo $product_id; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                        </td>
                                        <td data-label="Delete">
                                            <a href="DeleteProductCart.php?id=<?php echo $product_id; ?>" class="delete-btn">
                                                <i class="far fa-times-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-actions">
                    <a href="emptyCart.php">
                        <button type="button" class="action-btn">
                            <i class="fas fa-trash"></i> Empty Cart
                        </button>
                    </a>
                    
                    <a href="bhome.php">
                        <button type="button" class="action-btn">
                            <i class="fas fa-shopping-bag"></i> Continue Shopping
                        </button>
                    </a>
                    
                    <button type="submit" name="submit_borrow_request" class="action-btn checkout-btn">
                        <i class="fas fa-paper-plane"></i> Submit Borrow Request
                    </button>
                </div>
            </form>
        <?php
            } else {
                echo "
                <div class='empty-cart'>
                    <i class='fas fa-shopping-cart'></i>
                    <h2>Your Cart is Empty</h2>
                    <p>Add some books to your cart to get started!</p>
                    <br>
                    <a href='bhome.php'>
                        <button class='action-btn'>
                            <i class='fas fa-book'></i> Browse Books
                        </button>
                    </a>
                </div>
                ";
            }
        } else {
            echo "
            <div class='empty-cart'>
                <i class='fas fa-user-lock'></i>
                <h2>Please Login First</h2>
                <p>You need to login to view your cart</p>
                <br>
                <a href='../auth/UserLogin.php'>
                    <button class='action-btn'>
                        <i class='fas fa-sign-in-alt'></i> Login
                    </button>
                </a>
            </div>
            ";
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