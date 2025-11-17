<?php
include("../Functions/functions.php");

// Ensure the database connection is available
global $con;

// --- Handle "Mark as Done" Deletion ---
if (isset($_POST['mark_done'])) {
    $order_id_to_delete = mysqli_real_escape_string($con, $_POST['order_id']);
    
    // SQL to delete the record from the orders table
    $delete_query = "DELETE FROM orders WHERE order_id = '$order_id_to_delete'";
    
    if (mysqli_query($con, $delete_query)) {
        // Success: Redirect to refresh the page without the deleted item
        echo "<script>alert('Transaction marked as done and cleared.');</script>";
        echo "<script>window.open('Transactions.php','_self')</script>";
    } else {
        // Error: Show error message
        echo "<script>alert('Failed to mark transaction as done: " . mysqli_error($con) . "');</script>";
        echo "<script>window.open('Transactions.php','_self')</script>";
    }
}
// ----------------------------------------
?>

<!DOCTYPE html>

<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Librarian - Transactions</title>
     
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
               font-size: 25px !important;
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

          /* --- Main Navigation Buttons (Replaces old .row navigation) --- */
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
          
          /* --- Content & Transaction Table Styling (Modernized) --- */
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

          .table {
               width: 100%;
               border-collapse: collapse;
               border-radius: 15px; 
               overflow: hidden; 
               box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
          }

          .table td,
          .table th {
               padding: 15px 15px;
               border: 1px solid #ddd;
               text-align: center;
               font-size: 16px;
               vertical-align: middle;
          }

          .table th {
               background-color: #292b2c;
               color: goldenrod;
               font-weight: 700;
               text-transform: uppercase;
          }

          .table tbody tr:nth-child(even) {
               background-color: #f5f5f5;
          }
          
          .table tbody tr:hover {
               background-color: #e9ecef;
               transition: background-color 0.3s ease;
          }

          /* Mark as Done Button */
          .btn-done {
              background: #dc3545; /* Red color for completion/deletion */
              color: white;
              font-weight: 700;
              border: none;
              padding: 8px 15px;
              border-radius: 8px;
              transition: all 0.3s ease;
          }
          
          .btn-done:hover {
              background: #c82333;
              transform: translateY(-1px);
              box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
          }

          /* Mobile Table Responsiveness */
          @media only screen and (min-device-width:320px) and (max-device-width:480px) {
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
                    background: white;
               }

               .table td {
                    text-align: right;
                    padding-left: 50%;
                    position: relative;
                    border: none;
                    border-bottom: 1px dashed #eee;
               }
               
               .table td:last-child {
                   border-bottom: 0;
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
                    color: #292b2c;
               }

               /* Hide desktop navigation in mobile */
               .right { display: none; }
               .left { display: flex; }
               .moblogo { display: none; }
               
               .content_item label { font-size: 1.8rem; }
          }
          
          /* --- Footer --- */
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

          .list-inline-item a {
               color: #ffc107;
               font-size: 24px;
               transition: all 0.3s ease;
          }

          .list-inline-item a:hover {
               color: #28a745;
               transform: scale(1.2);
          }
          
     </style>

</head>

<body>

    <nav class="navbar navbar-expand-xl">
        <div class="d-flex align-items-center d-xl-none">
            <a class="navbar-brand" href="farmerHomepage.php">
                <h1 class="book-corner-logo">Smart Library Management System</h1>
            </a>
            <i class='far fa-user-circle user-icon ml-auto mr-3'></i>
        </div>
        
        <a class="navbar-brand d-none d-xl-block" href="farmerHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo">
        </a>


        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars p-1" style="color:goldenrod;font-size:20px;"></i></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <?php getFarmerUsername(); ?>

            <div class="list-group moblists">
                <?php
                if (isset($_SESSION['phonenumber'])) {
                    echo "<a href='FarmerProfile2.php' class='list-group-item list-group-item-action ' >Profile</a>"; 
                    echo "<a href='MyProducts.php' class='list-group-item list-group-item-action'>My Books</a>";
                    echo "<a href='Transactions.php' class='list-group-item list-group-item-action'>My Transactions (Orders)</a>";
                    echo "<a href='bid_insert.php' class='dropdown-item'>Bid</a>";
                    echo "<a href='display_bids2.php' class='dropdown-item'>Bid Message</a>";
                    echo "<a href='online_class.php' class='dropdown-item'>Post Meet & Greet</a>";
                    echo "<a href='leaderboard.php' class='dropdown-item'>See Quiz Results</a>";
                    echo "<a href='donate_book.php' class='list-group-item list-group-item-action'>Donation</a>";
                    echo "<a href='viewclaim.php' class='list-group-item list-group-item-action'>Donation Message</a>";
                    echo "<a href='logout.php' class='list-group-item list-group-item-action '>Logout</a>";
                } else {
                    echo "<a href='../auth/FarmerLogin.php' class='list-group-item list-group-item-action'>Login</a>";
                }
                ?>
            </div>
               
            <div class="d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px; margin-left: auto; margin-right: 150px;"> 
                
                <div class="dropdown">
                    <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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


    <br>

    <div class="content_item">
        <label><b>TRANSACTION HISTORY</b></label>
    </div>


    <div class="container">

        <table class="table">
            <thead>
                <th>Book Name</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Delivery Address</th>
                <th>Quantity</th>
                <th>Action</th> </thead>


            <tbody>
                <?php

                global $con;
                include("../Includes/db.php"); 
                
                if (isset($_SESSION['phonenumber'])) {
                    $sess_phone_number = $_SESSION['phonenumber'];
                    
                    // Fetch orders where the current librarian (farmer) is the recipient/seller
                    $get_farmer_id_query = "SELECT farmer_id FROM farmerregistration WHERE farmer_phone = '$sess_phone_number'";
                    $run_farmer_id = mysqli_query($con, $get_farmer_id_query);
                    $farmer_row = mysqli_fetch_array($run_farmer_id);
                    $current_farmer_id = $farmer_row['farmer_id'];
                    
                    // Select orders made to this farmer
                    $sel_orders = "SELECT o.order_id, o.product_id, o.qty, o.address, o.buyer_phonenumber, p.product_title 
                                   FROM orders o
                                   JOIN products p ON o.product_id = p.product_id
                                   WHERE p.farmer_fk = '$current_farmer_id'";
                                   
                    $run_orders = mysqli_query($con, $sel_orders);
                    $i = 0;

                    while ($order_data = mysqli_fetch_array($run_orders)) {
                        $order_id = $order_data['order_id'];
                        $product_id = $order_data['product_id'];
                        $qty = $order_data['qty'];
                        $address = $order_data['address'];
                        $buyer_phone = $order_data['buyer_phonenumber'];
                        $product_title = $order_data['product_title'];


                        // Get buyer name from buyerregistration
                        $query_name = "select buyer_name from buyerregistration where buyer_phone = '$buyer_phone'";
                        $run_query_name = mysqli_query($con, $query_name);
                        $names = mysqli_fetch_array($run_query_name);
                        $buyer_name = $names['buyer_name'];
                        
                        
                ?>
                                    <tr>
                                        <td data-label="Book Name"><?php echo htmlspecialchars($product_title); ?></td>
                                        <td data-label="Name"><?php echo htmlspecialchars($buyer_name); ?></td>
                                        <td data-label="Phone Number"><?php echo htmlspecialchars($buyer_phone); ?></td>
                                        <td data-label="Delivery Address"><?php echo htmlspecialchars($address); ?></td>
                                        <td data-label="Quantity"><?php echo htmlspecialchars($qty); ?></td>
                                        <td data-label="Action">
                                            <form method="post" action="Transactions.php" onsubmit="return confirm('Are you sure you want to mark this transaction as done? This action cannot be undone.');">
                                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                                                <button type="submit" name="mark_done" class="btn-done">Mark as Done</button>
                                            </form>
                                        </td>
                                    </tr>
                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6'><h4 align='center'>Please Login First!</h4></td></tr>";
                } 
                
                // If there are no results, but the user is logged in, show a message
                if ($i == 0 && isset($_SESSION['phonenumber'])) {
                    echo "<tr><td colspan='6'><h4 align='center'>You have no pending transactions.</h4></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div> 
    
    <br> <br>







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
                    <p>Smart Library Management System - A digital library management system</p>
                    <p class="h6"><a class="text-green ml-2" target="_blank">Foreign Key Friends</a></p>
                </div>
                </hr>
            </div>
        </div>
    </section>

</body>

</html>