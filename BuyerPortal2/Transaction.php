<?php
include("../Functions/functions.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader - Transactions</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>

    <style>
        /* --- Styles copied from bhome.php for consistent look --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        /* Table Styling */
        .container {
            flex-grow: 1;
            margin-top: 30px;
        }

        .transactions-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .transactions-header h3 {
            font-size: 2.2rem;
            font-weight: 800;
        }


        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            padding: 12px 15px;
            border: none;
            text-align: center;
            font-size: 16px;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #292b2c;
            color: goldenrod;
            font-weight: 700;
            font-size: 1rem;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        
        /* Continue Shopping Button */
        .btn-shopping {
            background-color:#FFD700;
            color:#1a1a2e;
            border: 2px solid #1a1a2e;
            font-weight: 700;
            padding: 12px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-shopping:hover {
            background-color: #e0b000;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: #1a1a2e;
            text-decoration: none;
        }


        /* Footer Styling */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: auto;
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

        /* Responsive Table */
        @media only screen and (max-device-width:768px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none;
                border-bottom: 1px solid #eee;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
                color: #1a1a2e;
            }
            .table tbody tr td:last-child {
                border-bottom: none;
            }
        }
        
        /* Mobile Navbar Icons */
        .left { display: flex; }
        .right { display: none; }
        .moblogo { display: none; }

        @media (min-width: 1200px) {
            .left { display: none; }
            .right { display: flex; }
            .d-none { display: none !important; }
            .d-xl-flex { display: flex !important; }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
        }

        .toast {
            min-width: 300px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            font-size: 16px;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideIn 0.5s forwards, fadeOut 0.5s 4s forwards;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<script>
    function showToast(message) {
        const container = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerText = message;

        container.appendChild(toast);

        setTimeout(() => { toast.remove(); }, 5000);
    }
</script>

<body>
    <div class="toast-container" id="toastContainer"></div>

    <?php
        if (isset($_GET['borrowed']) && $_GET['borrowed'] == 1) {
            echo "<script>showToast('Borrow request submitted successfully! Your items are now being processed.');</script>";
        }
    ?>

    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="bhome.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>

            <div class="d-xl-none" style="display: flex; align-items: center; gap: 15px;">
                <i class='far fa-user-circle user-icon'></i>
                <a href="CartPage.php" style="position: relative;">
                    <i class="fa fa-shopping-cart cart-icon"></i>
                    <span id="icon"><?php echo totalItems(); ?></span>
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-bars" style="color:#28a745; font-size:28px;"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
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

                <?php getUsername(); ?>

                <div class="ml-auto d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px;">
                    <a href="voice_search.php" class="btn btn-success" style="padding: 8px 20px;">
                        <i class="fas fa-microphone"></i>
                    </a>

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

                    <i class='far fa-user-circle user-icon'></i>

                    <a href="CartPage.php" style="position: relative;">
                        <i class="fa fa-shopping-cart cart-icon"></i>
                        <span id="icon"><?php echo totalItems(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="transactions-header">
            <h3>Your Borrowing History and Transactions</h3>
        </div>

        <?php
        // Ensure user is logged in before proceeding
        if (!isset($_SESSION['phonenumber'])) {
            echo "<h1 align = center>Please Login First!</h1><br><br><hr>";
        } else {
            // Transaction Table Content
        ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Librarian Name</th>
                        <th>Librarian Phone</th>
                        <th>Delivery Address</th>
                        <th>Book</th>
                        <th>Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    global $con;
                    $sess_phone_number = $_SESSION['phonenumber'];
                    $sel_price = "select * from orders where buyer_phonenumber = '$sess_phone_number'";
                    $run_price = mysqli_query($con, $sel_price);
                    $i = 0;

                    while ($p_price = mysqli_fetch_array($run_price)) {
                        $product_id = $p_price['product_id'];
                        $qty = $p_price['qty'];
                        $address = $p_price['address'];
                        $delivery = $p_price['delivery'];

                        $pro_price = "select * from products where product_id='$product_id'";
                        $run_pro_price = mysqli_query($con, $pro_price);
                        while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                            $product_title = $pp_price['product_title'];
                            $farmer_id = $pp_price['farmer_fk'];

                            $query_name = "select * from librarianregistration where farmer_id = $farmer_id";
                            $run_query_name = mysqli_query($con, $query_name);
                            while ($names = mysqli_fetch_array($run_query_name)) {
                                $farmer_name = $names['farmer_name'];
                                $farmer_phone = $names['farmer_phone'];
                            }
                    ?>
                            <tr>
                                <td data-label="Librarian Name"><?php echo htmlspecialchars($farmer_name); ?> </td>
                                <td data-label="Librarian Phone"><?php echo htmlspecialchars($farmer_phone); ?> </td>
                                <td data-label="Delivery Address"><?php echo htmlspecialchars($address); ?> </td>
                                <td data-label="Book"><?php echo htmlspecialchars($product_title); ?> </td>
                                <td data-label="Quantity"><?php echo htmlspecialchars($qty); ?> </td>
                            </tr>
                    <?php
                        }
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-left mt-3">
            <a href="bhome.php" class="btn btn-shopping">
                Continue Browsing Books
                <i class="fas fa-shopping-bag ml-2" aria-hidden="true"></i>
            </a>
        </div>

        <?php } // End of session check else block ?>
    </div>


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
                    <p class="h6">Copyright © All Rights Reserved. Foreign Key Friends</p>
                </div>
            </div>
        </div>
    </section>

</body>

</html>