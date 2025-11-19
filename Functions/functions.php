<?php

    session_start();

    include("../Includes/db.php");
    
    // Ensure $con is globally accessible if needed outside functions
    global $con; 

    function getUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            // FIX: Ensure quoting for phone number when retrieving username
            $query = "select * from userregistration where buyer_phone = '$phonenumber'";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['buyer_name'];
                    $buyer_name = 'Hello ,' . $buyer_name;
                }

                echo @"<div class='text-success  logins mx-1 ml-5  '>$buyer_name</div>";
            }
        } else {
            echo "<a href = '../auth/UserLogin.php'><div class='text-success logins mx-5'>Login</div></a>";
        }
    }


    function getFarmerUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            // FIX: Ensure quoting for phone number when retrieving farmer username
            $query = "select * from librarianregistration where farmer_phone = '$phonenumber'";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['farmer_name'];
                    $buyer_name = "Hello ," . $buyer_name;
                    echo "<label style = 'color:white; padding-top:7px;'>$buyer_name</label>";
                }
            }
        } else {
            echo "<label><a href = '../auth/LibrarianLogin.php' style = 'color:white; padding-top:20px;' >Login/Sign up</a></label>";
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
            echo "<a class='dropdown-item' href='../UserPortal/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getFruits()
    {

        global $con;

        $query = "select * from products where product_cat = 3 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../UserPortal/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getVegetables()
    {

        global $con;

        $query = "select * from products where product_cat = 2 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../UserPortal/Categories.php?type=$product_type'>$product_type</a>";
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

        $today_dt = new DateTime(date('Y-m-d'));

        $query = "SELECT 
                    p.product_title, 
                    c.return_date 
                FROM 
                    orders c
                JOIN 
                    products p ON c.product_id = p.product_id
                WHERE 
                    c.buyer_phonenumber = '$phonenumber' 
                    AND c.borrow_date IS NOT NULL
                    AND c.return_date IS NOT NULL";

        $run_query = mysqli_query($con, $query);

        if ($run_query && mysqli_num_rows($run_query) > 0) {
            
            $alerts_html = "";

            while ($row = mysqli_fetch_array($run_query)) {
                $product_title = $row['product_title'];
                $return_date_str = $row['return_date'];

                if (empty($return_date_str)) continue;

                $return_dt = new DateTime($return_date_str);
                
                $interval = $today_dt->diff($return_dt);
                $days_diff = (int)$interval->format('%r%a');

                if ($days_diff > 0) {
                    $message = "<strong>$product_title:</strong> Due in $days_diff day(s).";
                    $alert_class = 'borrow-alert-success';
                } elseif ($days_diff == 0) {
                    $message = "<strong>$product_title:</strong> Due today.";
                    $alert_class = 'borrow-alert-warning';
                } else {
                    $days_overdue = abs($days_diff);
                    $message = "<strong>$product_title:</strong> Overdue by $days_overdue day(s).";
                    $alert_class = 'borrow-alert-danger';
                }

                $alerts_html .= "<div class='borrow-alert $alert_class' role='alert'>$message</div>";
            }

            if (!empty($alerts_html)) {
                echo "
                <style>
                    .borrow-alert-container {
                        position: fixed;
                        top: 80px; 
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
                    .borrow-alert-success { 
                        color: #155724;
                        background-color: #d4edda;
                        border-color: #c3e6cb;
                    }
                    .borrow-alert-warning { 
                        color: #856404;
                        background-color: #fff3cd;
                        border-color: #ffeeba;
                    }
                    .borrow-alert-danger { 
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
                            setTimeout(() => {
                                alert.style.opacity = '0';
                                
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
        
        $farmer_name_query = "select farmer_name from librarianregistration where farmer_id = $farmer_fk";
        $running_query_name = mysqli_query($con, $farmer_name_query);
        while ($names = mysqli_fetch_array($running_query_name)) {
            $name = $names['farmer_name'];
        }

        echo "
        <div class='col-md-4 col-sm-6 mb-4'>
            <div class='book-card'>
                <a href='../UserPortal/Categories.php?type=$product_type'>
                    <img src='../Admin/product_images/$product_image' alt='$product_title' class='img-fluid' style='width: 100%; height: 350px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;'>
                </a>
                
                <div class='card-body'>
                    <h5 style='font-weight: 700; color: #1a1a2e; margin-bottom: 10px; font-size: 1.2rem;'>$product_title</h5>
                    <span class='badge' style='background: #ffc107; color: #000; padding: 5px 15px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; margin-bottom: 15px;'>$product_type</span>
                    
                    <a href='../UserPortal/bhome.php?add_cart=$product_id' class='btn-add-cart' style='background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; width: 100%; display: block; text-align: center; text-decoration: none; transition: all 0.3s ease;'>
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

            echo "<div class='column kolum'>
                <div class='img-thumbnail ''>
                     <a href='../UserPortal/Categories.php?type=$product_type'>
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
                     <a href='../UserPortal/Categories.php?type=$product_type'>
                        <img class='rounded mx-auto d-block images' src='../Admin/product_images//$product_image' width='350px' height='200px' alt='image'>
                     </a>
                </div>
            </div>";
        }
    }
    
    function getFarmerProductDetails()
    {
        include("../Includes/db.php");
        global $con;
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

                        $query = "select * from librarianregistration where farmer_id = $farmerid";
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


// UPDATED cart() FUNCTION (from the request and previous fix)
function cart()
{
    if (isset($_SESSION['phonenumber'])) {
        if (isset($_GET['add_cart'])) {

            global $con;
            $qty = 1; 
            
            $sess_phone_number = $_SESSION['phonenumber'];
            $product_id = $_GET['add_cart'];

            // FIX 1: Ensure quoting for session phone number.
            $check_pro = "select * from cart where phonenumber = '$sess_phone_number' and product_id='$product_id' ";

            $run_check = mysqli_query($con, $check_pro);

            if (mysqli_num_rows($run_check) > 0) {
                echo "";
            } else {
                // FIX 2: Ensure all required columns (qty, subtotal) are included.
                $insert_pro = "insert into cart (product_id,phonenumber, qty, subtotal) 
                               values ('$product_id','$sess_phone_number', '$qty', 0)";
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
        $query = "select * from products where farmer_fk in (select farmer_id from librarianregistration where farmer_phone='$sess_phone_number') ";
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
                        <a href='../LibrarianPortal/FarmerProductDetails.php?id=$id'>
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

                        $query = "select * from librarianregistration where farmer_id = $farmerid";
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
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];

            $get_items = "Delete from cart where phonenumber = '$sess_phone_number'";
            $run_items =  mysqli_query($con, $get_items);
        }
    }


    function bestSeller()
    {
        global $con;
    }

    /**
     * Gets the total number of registered users (buyers).
     * @return int The total count of users.
     */
    function getTotalUsers() {
        global $con;
        $count = 0;
        $query_users = "SELECT COUNT(*) as total FROM userregistration";
        $run_users = mysqli_query($con, $query_users);
        if ($run_users) {
            $count = mysqli_fetch_assoc($run_users)['total'];
        }
        return (int)$count;
    }

    /**
     * Gets the total number of books (products).
     * @return int The total count of books.
     */
    function getTotalBooks() {
        global $con;
        $count = 0;
        $query_books = "SELECT COUNT(*) as total FROM products";
        $run_books = mysqli_query($con, $query_books);
        if ($run_books) {
            $count = mysqli_fetch_assoc($run_books)['total'];
        }
        return (int)$count;
    }

    /**
     * Gets the total number of rare books (bids).
     * @return int The total count of rare books.
     */
    function getTotalRareBooks() {
        global $con;
        $count = 0;
        $query_rare = "SELECT COUNT(*) as total FROM bid";
        $run_rare = mysqli_query($con, $query_rare);
        if ($run_rare) {
            $count = mysqli_fetch_assoc($run_rare)['total'];
        }
        return (int)$count;
    }

    /**
     * Gets the total number of borrowed books (completed loans in cart).
     * @return int The total count of borrowed books.
     */
    function getTotalBorrowedBooks() {
        global $con;
        $count = 0;
        $query_borrowed = "SELECT COUNT(*) as total FROM orders WHERE borrow_date IS NOT NULL AND return_date IS NOT NULL";
        $run_borrowed = mysqli_query($con, $query_borrowed);
        if ($run_borrowed) {
            $count = mysqli_fetch_assoc($run_borrowed)['total'];
        }
        return (int)$count;
    }
    ?>


