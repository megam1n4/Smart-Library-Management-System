 <?php

    session_start();

    include("../Includes/db.php");

    function getUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            $query = "select * from buyerregistration where buyer_phone = $phonenumber";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['buyer_name'];
                    $buyer_name = 'Hello ,' . $buyer_name;
                }

                // echo @"<label>$buyer_name</label>";
                echo @"<div class='text-success  logins mx-1 ml-5  '>$buyer_name</div>";
            }
        } else {
            echo "<a href = '../auth/UserLogin.php'><div class='text-success logins mx-5'>Login</div></a>";
            // echo "<label><a href = '../auth/UserLogin.php' style = 'color:white' >Login/Sign up</a></label>";
        }
    }


    function getFarmerUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            $query = "select * from farmerregistration where farmer_phone = $phonenumber";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['farmer_name'];
                    $buyer_name = "Hello ," . $buyer_name;
                    echo "<label style = 'color:white; padding-top:7px;'>$buyer_name</label>";
                }
            }
        } else {
            echo "<label><a href = '../auth/FarmerLogin.php' style = 'color:white; padding-top:20px;' >Login/Sign up</a></label>";
        }
    }

    function CheckoutIdentify()
    {
        if (isset($_SESSION['phonenumber'])) {
            echo "<script>window.open('CartPage.php','_self')</script>";
        } else {
            echo "<script>window.open('../auth/BuyerLogin.php','_self')</script>";
        }
    }


    function getCrops()
    {

        global $con;

        $query = "select * from products where product_cat = 1 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getFruits()
    {

        global $con;

        $query = "select * from products where product_cat = 3 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            // echo "<li class='options' role='presentation'><a role='menuitem' tabindex='-1' href='../BuyerPortal/Categories.php?type=$product_type'> 
            //         <label class='crop_items'>$product_type</label></a></li>";

            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getVegetables()
    {

        global $con;

        $query = "select * from products where product_cat = 2 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    /**
     * Displays auto-disappearing alerts for borrowed items.
     * Checks the 'cart' table for items with a borrow_date and return_date
     * for the currently logged-in user.
     * - Green Alert: Item is due in the future.
     * - Yellow Alert: Item is due today.
     * - Red Alert: Item is overdue.
     */
    function borrowedBooksAlerts() {
        // Only run if a user is logged in
        if (!isset($_SESSION['phonenumber'])) {
            return;
        }

        global $con;
        $phonenumber = $_SESSION['phonenumber'];

        // Get today's date for comparison
        $today_dt = new DateTime(date('Y-m-d'));

        // Query to get all 'borrowed' items for the user, joining with products to get the title
        $query = "SELECT 
                    p.product_title, 
                    c.return_date 
                FROM 
                    cart c
                JOIN 
                    products p ON c.product_id = p.product_id
                WHERE 
                    c.phonenumber = '$phonenumber' 
                    AND c.borrow_date IS NOT NULL
                    AND c.return_date IS NOT NULL";

        $run_query = mysqli_query($con, $query);

        if ($run_query && mysqli_num_rows($run_query) > 0) {
            
            $alerts_html = ""; // To store our alert messages

            while ($row = mysqli_fetch_array($run_query)) {
                $product_title = $row['product_title'];
                $return_date_str = $row['return_date'];

                // Skip if return date is invalid
                if (empty($return_date_str)) continue;

                $return_dt = new DateTime($return_date_str);
                
                // Calculate the difference in days
                $interval = $today_dt->diff($return_dt);
                $days_diff = (int)$interval->format('%r%a'); // %r gives sign (+/-), %a gives total days

                if ($days_diff > 0) {
                    // Due in the future
                    $message = "<strong>$product_title:</strong> Due in $days_diff day(s).";
                    $alert_class = 'borrow-alert-success'; // Green
                } elseif ($days_diff == 0) {
                    // Due today
                    $message = "<strong>$product_title:</strong> Due today.";
                    $alert_class = 'borrow-alert-warning'; // Yellow
                } else {
                    // Overdue
                    $days_overdue = abs($days_diff);
                    $message = "<strong>$product_title:</strong> Overdue by $days_overdue day(s).";
                    $alert_class = 'borrow-alert-danger'; // Red
                }

                $alerts_html .= "<div class='borrow-alert $alert_class' role='alert'>$message</div>";
            }

            if (!empty($alerts_html)) {
                echo "
                <style>
                    .borrow-alert-container {
                        position: fixed;
                        top: 80px; /* Adjust as needed */
                        right: 20px;
                        z-index: 1050;
                        width: 320px;
                    }
                    .borrow-alert {
                        padding: 1rem;
                        margin-bottom: 10px;
                        border: 1px solid transparent;
                        border-radius: 0.25rem;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        opacity: 1;
                        transition: opacity 0.5s ease-out;
                    }
                    .borrow-alert-success { /* Green */
                        color: #155724;
                        background-color: #d4edda;
                        border-color: #c3e6cb;
                    }
                    .borrow-alert-warning { /* Yellow */
                        color: #856404;
                        background-color: #fff3cd;
                        border-color: #ffeeba;
                    }
                    .borrow-alert-danger { /* Red */
                        color: #721c24;
                        background-color: #f8d7da;
                        border-color: #f5c6cb;
                    }
                </style>

                <div class='borrow-alert-container'>
                    $alerts_html
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const alerts = document.querySelectorAll('.borrow-alert');
                        
                        alerts.forEach((alert, index) => {
                            // Set a timeout to fade the alert
                            setTimeout(() => {
                                alert.style.opacity = '0';
                                
                                // Set another timeout to remove the alert from the DOM after fading
                                setTimeout(() => {
                                    if (alert.parentNode) {
                                        alert.parentNode.removeChild(alert);
                                    }
                                }, 600); // 0.6s (must be > transition duration)

                            }, 5000 + (index * 1000)); // 5s base, +1s for each additional alert
                        });
                    });
                </script>
                ";
            }
        }
    }

    
// UPDATED getProducts() FUNCTION
function getProducts()
{
    global $con;
    $query = "select * from products order by RAND() LIMIT 0,6";
    $run_query = mysqli_query($con, $query);
    
    while ($rows = mysqli_fetch_array($run_query)) {
        $product_id = $rows['product_id'];
        $product_title = $rows['product_title'];
        $product_image = $rows['product_image'];
        $product_price = $rows['product_price'];
        $product_type = $rows['product_type'];
        $farmer_fk = $rows['farmer_fk'];
        
        // Get farmer name
        $farmer_name_query = "select farmer_name from farmerregistration where farmer_id = $farmer_fk";
        $running_query_name = mysqli_query($con, $farmer_name_query);
        while ($names = mysqli_fetch_array($running_query_name)) {
            $name = $names['farmer_name'];
        }

        echo "
        <div class='col-md-4 col-sm-6 mb-4'>
            <div class='book-card'>
                <a href='../BuyerPortal2/Categories.php?type=$product_type'>
                    <img src='../Admin/product_images/$product_image' alt='$product_title' class='img-fluid' style='width: 100%; height: 350px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;'>
                </a>
                
                <div class='card-body'>
                    <h5 style='font-weight: 700; color: #1a1a2e; margin-bottom: 10px; font-size: 1.2rem;'>$product_title</h5>
                    <span class='badge' style='background: #ffc107; color: #000; padding: 5px 15px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; margin-bottom: 15px;'>$product_type</span>
                    
                    <a href='../BuyerPortal2/bhome.php?add_cart=$product_id' class='btn-add-cart' style='background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; width: 100%; display: block; text-align: center; text-decoration: none; transition: all 0.3s ease;'>
                        <i class='fas fa-shopping-cart'></i> Add to cart
                    </a>
                </div>
            </div>
        </div>
        ";
    }
}

    function getVegetablesHomepage()
    {
        global $con;
        $query = "select * from products where product_cat = 2 and not (product_image = '') order by RAND() LIMIT 0,4";
        $run_query = mysqli_query($con, $query);
        while ($rows = mysqli_fetch_array($run_query)) {
            $product_id = $rows['product_id'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_delivery = $rows['product_delivery'];
            $product_cat = $rows['product_cat'];
            $product_type = $rows['product_type'];

            // echo "  <div class='veg'>
            //             <a href='../BuyerPortal/BuyerProductDetails.php?id=$product_id'><img src='../Admin/product_images/$product_image' height='250px' width='300px' ></a>
            //         </div>";

            echo "<div class='column kolum'>
                <div class='img-thumbnail ''>
                     <a href='../BuyerPortal2/Categories.php?type=$product_type'>
                        <img class='rounded mx-auto d-block images' src='../Admin/product_images//$product_image' width='350px' height='200px' alt='image'>
                     </a>
                </div>
            </div>";
        }
    }

    function getFruitsHomepage()
    {
        global $con;
        $query = "select * from products where product_cat = 3 and not (product_image = '') order by RAND() LIMIT 0,4";
        $run_query = mysqli_query($con, $query);
        while ($rows = mysqli_fetch_array($run_query)) {
            $product_id = $rows['product_id'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_delivery = $rows['product_delivery'];
            $product_cat = $rows['product_cat'];
            $product_type = $rows['product_type'];
            echo "<div class='column kolum'>
                <div class='img-thumbnail ''>
                     <a href='../BuyerPortal2/Categories.php?type=$product_type'>
                        <img class='rounded mx-auto d-block images' src='../Admin/product_images//$product_image' width='350px' height='200px' alt='image'>
                     </a>
                </div>
            </div>";
        }
    }
    //function  which is link with FarmerProductDetails
    // function getFarmerProductDetails()
    // {
    //     include("../Includes/db.php");
    //     global $con;
    //     if (isset($_GET['id'])) {
    //         $prod_id = $_GET['id'];
    //         $query = "select * from products where product_id=" . $prod_id;
    //         $run_query = mysqli_query($con, $query);
    //         $resultCheck = mysqli_num_rows($run_query);
    //         if ($resultCheck > 0) {
    //             while ($rows = mysqli_fetch_array($run_query)) {
    //                 $product_title = $rows['product_title'];
    //                 $product_image = $rows['product_image'];
    //                 $product_type = $rows['product_type'];
    //                 $product_stock = $rows['product_stock'];
    //                 $product_description = $rows['product_desc'];
    //                 $product_price = $rows['product_price'];
    //                 $product_delivery = $rows['product_delivery'];
    //                 $product_cat = $rows['product_cat'];
    //                 echo "<div>
    //                 <img src='../Admin/product_images/$product_image' height='250px' width='300px' ><br>"
    //                     . " product title  :  " . $product_title . "<br>"
    //                     . " product type  :  " . $product_type . "<br>"
    //                     . " product stock  :  " . $product_stock . "<br>"
    //                     . " product Description  :  " . $product_description . "<br>"
    //                     . " product price  :  " . $product_price . "<br>"
    //                     . " product Delivery  :  " . $product_delivery . "<br>"
    //                     . " product category  :  " . $product_cat . "<br>"
    //                     . "</div>";
    //             }
    //         }
    //     } else {
    //         echo "<br><br><hr><h1 align = center>Product Not Uploaded !</h1><br><br><hr>";
    //     }
    // }

    // Checkout System Functions

// UPDATED cart() FUNCTION
// In functions.php, locate the cart() function and update it:
function cart()
{
    if (isset($_SESSION['phonenumber'])) {
        if (isset($_GET['add_cart'])) {

            global $con;
            $qty = 1; // Default quantity is 1 (no quantity input field)
            
            $sess_phone_number = $_SESSION['phonenumber'];
            $product_id = $_GET['add_cart'];

            // FIX: Added quotes around $sess_phone_number to ensure correct SQL syntax.
            $check_pro = "select * from cart where phonenumber = '$sess_phone_number' and product_id='$product_id' ";

            $run_check = mysqli_query($con, $check_pro);

            if (mysqli_num_rows($run_check) > 0) {
                echo "";
            } else {
                // FIX: Added 'subtotal' column to the INSERT statement, initialized to 0. 
                // This ensures consistency, as the cart table has a NOT NULL constraint on subtotal.
                $insert_pro = "insert into cart (product_id,phonenumber, qty, subtotal) values ('$product_id','$sess_phone_number', '$qty', 0)";
                $run_insert_pro = mysqli_query($con, $insert_pro);
            }

            echo "<script>window.open('bhome.php?added=$product_id','_self')</script>";
        }
    } else {
        // Optionally, you can add an alert here to notify the user to log in.
    }
}

    //function which is link with FarmerHomePage
    function getFarmerProducts()
    {
        include("../Includes/db.php");
        global $con;
        $sess_phone_number = $_SESSION['phonenumber'];
        $query = "select * from products where farmer_fk in (select farmer_id from farmerregistration where farmer_phone=$sess_phone_number) ";
        $run_query = mysqli_query($con, $query);
        $count = 0;
        if ($run_query) {
            while ($row = mysqli_fetch_assoc($run_query)) {
                $count = $count + 1;
                $product_title =  $row['product_title'];
                $image =  $row['product_image'];
                $price =  $row['product_price'];
                $id =     $row['product_id'];
                $path = "../Admin/product_images/" . $image;

                echo "
                    <div class='productbox'>
                        <a href='../FarmerPortal/FarmerProductDetails.php?id=$id'>
                        <img src='../Admin/product_images/$image' alt= 'Image Not Available' onerror=this.src='../Images/Website/noimage.jpg'>
                        </a>

                        <div>
                            <p><b>$product_title</b></p>
                        </div>

                    </div>";
            }
        } else {
            echo "<br><br><hr><h1 align = center>Product Not Uploaded !</h1><br><br><hr>";
        }
    }
    //function which is linked with BuyerProductDetails
    function getBuyerProductDetails()
    {
        include("../Includes/db.php");
        global $con;
        // $sess_phone_number = $_SESSION['phonenumber'];
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
                    $product_cat = $rows['product_cat'];
                    echo "<div>
                        <img src='../Admin/product_images/$product_image' height='250px' width='300px' ><br>"
                        . " product title  :  " . $product_title . "<br>"
                        . " product type  :  " . $product_type . "<br>"
                        . " product stock  :  " . $product_stock . "<br>"
                        . " product Description  :  " . $product_description . "<br>"
                        . " product price  :  " . $product_price . "<br>"
                        . " product Delivery  :  " . $product_delivery . "<br>"
                        . " product category  :  " . $product_cat . "<br>"
                        . "<button href=''>ADD TO CART</button>"
                        . "</div>";

                    if (isset($_SESSION['phonenumber'])) {
                        $query = "select * from products where product_id=" . $prod_id;
                        $run = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_array($run)) {
                            $farmerid = $row['farmer_fk'];
                        }

                        $query = "select * from farmerregistration where farmer_id = $farmerid";
                        $run = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_array($run)) {
                            $farmer_name = $row['farmer_name'];
                            $farmer_phone = $row['farmer_phone'];
                            $farmer_address = $row['farmer_address'];
                        }
                        echo "farmer Name : " . $farmer_name . "<br>farmer Phone Number : " . $farmer_phone . "<br> Farmer Address" . $farmer_address;
                    }
                }
            }
        }
    }


    function totalItems()
    {
        global $con;
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];

            $get_items = "select * from cart where phonenumber = '$sess_phone_number'";
            $run_items =  mysqli_query($con, $get_items);
            $count_items =  mysqli_num_rows($run_items);
            return $count_items;
        } else {
            echo 0;
        }
    }


    function emptyCart()
    {
        global $con;
        $sess_phone_number = $_SESSION['phonenumber'];

        $get_items = "Delete from cart where phonenumber = '$sess_phone_number'";
        $run_items =  mysqli_query($con, $get_items);
        $count_items =  mysqli_num_rows($run_items);
    }


    function bestSeller()
    {
        global $con;
    }
    ?>


