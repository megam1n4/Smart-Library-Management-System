<?php
     // Include necessary functions and database connection
     include("../Functions/functions.php");
     
     // Define the maximum allowed quantity per item
     $MAX_QTY = 3; 

     if(isset($_GET['id'])) {
          $product_id = $_GET['id'];
          
          // 1. Fetch current quantity from the cart
          $sel_cart = "select * from cart where product_id = '$product_id'";
          $run_cart = mysqli_query($con, $sel_cart);
          
          $qty = 0;
          $price = 0;

          if ($p_price = mysqli_fetch_array($run_cart)) {
              $qty = $p_price['qty'];
          }

          // 2. Fetch product price for subtotal calculation (assuming 'products' table exists)
          $pro_price_query = "select product_price from products where product_id='$product_id'";
          $run_pro_price = mysqli_query($con, $pro_price_query);
          
          if ($pp_price = mysqli_fetch_array($run_pro_price)) {
              $price = $pp_price['product_price'];
          }
          
          // 3. CHECK FOR MAXIMUM QUANTITY LIMIT
          if ($qty < $MAX_QTY) {
              // If current quantity is less than max, increment and update the cart
              $qty += 1;
              $subtotal = $price * $qty;
              
              $update_cart = "update cart set qty = '$qty', subtotal = '$subtotal' where product_id = '$product_id'";
              $run_update = mysqli_query($con, $update_cart);

              if (!$run_update) {
                   // Optional: Add error handling for update failure
                   // echo "<script>alert('Database update failed: " . mysqli_error($con) . "');</script>";
              }
          } else {
              // If max quantity is reached, display an alert
              echo "<script>alert('Maximum quantity of $MAX_QTY books reached for this item.');</script>";
          }

          // Redirect back to the cart page
          echo "<script>window.open('CartPage.php','_self')</script>";
     }
?>