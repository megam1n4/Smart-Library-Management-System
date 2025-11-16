<?php
include("../Functions/functions.php");
include("../Includes/db.php");

// FIX: Conditionally start the session to avoid the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sess_phone_number = $_SESSION['phonenumber']; // Moved this line down since session is now guaranteed to be active.
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Librarian Product Details</title>
     
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
     <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>
     
     <style>
          /* --- Global & Layout Styling (Based on donate_book.php) --- */
          body {
               font-family: 'Inter', sans-serif;
               background: #f8f9fa;
               color: #333;
               min-height: 100vh;
               display: flex;
               flex-direction: column;
          }

          /* --- Top Logo Bar Styling (From donate_book.php) --- */
          .top-logo-bar {
               background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
               padding: 10px 20px;
               box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
               width: 100%;
               z-index: 100;
          }
          
          .top-logo-bar a {
               display: inline-block;
          }
          
          .top-logo-bar img {
               height: 50px;
               width: auto;
               object-fit: contain;
               background: white;
               padding: 5px;
               border-radius: 8px;
               transition: transform 0.3s ease;
          }

          .top-logo-bar img:hover {
               transform: scale(1.05);
          }

          /* --- Main Content Styling --- */
          .container {
               margin-top: 30px;
               padding: 0 15px;
          }

          .product-details-card {
               background-color: #ffffff;
               border-radius: 15px;
               box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
               overflow: hidden;
               margin-bottom: 30px;
          }

          .product-image-container {
               padding: 20px;
               text-align: center;
          }
          
          .product-image-container img {
               max-height: 350px;
               width: auto;
               object-fit: contain;
               border-radius: 10px;
               box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
          }

          .product-info {
               padding: 20px;
               background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%); /* Use blackgoldie style */
               color: white;
          }

          .product-info h1 {
               color: #ffc107; /* goldenrod */
               font-weight: 800;
               padding: 10px 0;
               border-bottom: 1px solid rgba(255, 193, 7, 0.3);
               margin-bottom: 15px;
               text-align: center;
          }
          
          .product-info h3 {
               color: white;
               font-size: 1.2rem;
               padding: 5px 0;
          }
          
          .stock-status {
               color: #20c997; /* Use green color for stock status */
               font-weight: 700;
               text-align: center;
               font-size: 1.5rem;
               margin-bottom: 15px;
          }
          
          .delivery-info {
               display: flex;
               align-items: center;
               justify-content: center;
               gap: 10px;
               margin-bottom: 20px;
          }

          .delivery-info i {
               color: #ffc107; /* goldenrod */
          }
          
          .action-buttons {
               display: flex;
               justify-content: space-around;
               padding: 10px 0;
               gap: 15px;
          }

          .action-buttons a {
               font-weight: 700;
               padding: 10px 20px;
               border-radius: 8px;
               transition: all 0.3s ease;
               color: black;
               text-decoration: none;
          }

          .action-buttons .btn-warning {
               background-color: #ffc107;
               border: none;
          }
          
          .action-buttons .btn-warning:hover {
               background-color: #e0a800;
               transform: translateY(-2px);
               box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          }
          
          .product-description-box {
               padding: 30px;
               background: #ffffff;
               border-radius: 15px;
               box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
               margin-top: 30px;
          }
          
          .product-description-box h3 {
               font-weight: 700;
               color: #292b2c;
               margin-bottom: 15px;
               border-bottom: 2px solid #e0e0e0;
               padding-bottom: 5px;
          }
          
          .product-description-box h5 {
               color: #555;
               line-height: 1.6;
          }


          /* --- Footer Styling (From donate_book.php) --- */
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
               background-color: white; /* Ensure payment logos are visible */
               padding: 5px;
          }

          .social li a {
               color: #ffc107;
               font-size: 24px;
               transition: all 0.3s ease;
          }
          
          .social li a:hover {
               color: #28a745;
          }

          /* Responsive Adjustments */
          @media (max-width: 992px) {
               .product-info {
                    border-radius: 0 0 15px 15px;
               }
               .action-buttons {
                    flex-direction: column;
               }
          }
          @media (min-width: 992px) {
               .product-info {
                    border-radius: 0 15px 15px 0;
               }
          }
     </style>
</head>

<body>
     <div class="top-logo-bar">
          <a href="farmerHomepage.php">
               <img src="logo2.jpg" alt="Smart Library Logo">
          </a>
     </div>

     <?php
     
     if (isset($_GET['id'])) {
          $prod_id = $_GET['id'];
          $query = "select * from products where product_id=" . $prod_id;
          $run_query = mysqli_query($con, $query);
          $resultCheck = mysqli_num_rows($run_query);
          
          if ($resultCheck > 0) {
               while ($rows = mysqli_fetch_array($run_query)) {
                    $product_title = $rows['product_title'];
                    $product_image = $rows['product_image'];
                    $product_type = $rows['product_type'];
                    $product_stock = $rows['product_stock'];
                    $product_description = $rows['product_desc'];
                    $product_price = $rows['product_price'];
                    $product_delivery = $rows['product_delivery'];
                    // $product_cat = $rows['product_cat']; // Not displayed, omitting for brevity

                    $stock_str = ($product_stock == 0) ? "Not In Stock" : "In Stock";
                    $delivery_str = ($product_delivery == "no") ? "Not Applicable" : "Yes, Applicable";
                    $space = "...."; // Used for indentation/design in original code

                    echo "
                    <div class='container'>
                         <div class='row product-details-card'>
                              
                              <div class='col-lg-6 product-image-container'>
                                   <img src='../Admin/product_images/$product_image' alt='$product_title' class='img-fluid'>      
                                   <h3 class='text-center mt-3' style='color:#1a1a2e; font-weight:700;'>$product_type</h3>
                              </div>

                              <div class='col-lg-6 product-info'>
                                   <div class='row'>
                                        <div class='col-md-12'>
                                             <h1>$product_title</h1>
                                        </div>
                                   </div>
                                   
                                   <div class='stock-status'>$stock_str</div>

                                   <div class='row'>
                                        <div class='col-md-12 text-center'>
                                             <h3>Product Stock: " . $product_stock . " units</h3>
                                        </div>
                                   </div>
                                   
                                   <div class='delivery-info'>
                                        <i class='fa fa-truck fa-2x'></i>
                                        <h3>Product Delivery: " . $delivery_str . "</h3>
                                   </div>

                                   <div class='action-buttons'>
                                        <a href='EditProduct.php?id=$prod_id' class='btn btn-warning'><b><i class='fas fa-edit'></i> Edit Book</b></a>
                                        <a href='Transactions.php' class='btn btn-warning'><b><i class='fas fa-receipt'></i> My Transactions</b></a>
                                   </div>
                              </div>         
                         </div>
                         
                         <div class='row'>
                              <div class='col-12'>
                                   <div class='product-description-box'>
                                        <h3><u><b>Book Description:</b></u></h3>
                                        <h5><span class='monospaced'>" . $space . $product_description . "</span></h5>
                                   </div>
                              </div>
                         </div>
                    </div>";
               }
          }
     } else {
          echo "<br><br><hr><h1 align = center>Product Not Uploaded!</h1><br><br><hr>";
     }
     ?>

     <section id="footer" class="myfooter">
          <div class="container-fluid">
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
                         <p class="h6">Copy All right Reversed. Foreign Key Friends</p>
                    </div>
               </div>
          </div>
     </section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>