<?php
include("../Includes/db.php");
session_start();
$sessphonenumber = $_SESSION['phonenumber'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Corner - Submit Rare Book Details</title>
    <style>
        /* Body background styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f8c291, #6a89cc); /* Gradient background */
            color: #333;
            animation: backgroundAnimation 10s ease infinite alternate; /* Animated gradient */
        }

        /* Background animation */
        @keyframes backgroundAnimation {
            0% { background: linear-gradient(135deg, #f8c291, #6a89cc); }
            100% { background: linear-gradient(135deg, #78e08f, #60a3bc); }
        }

        /* Container styling */
        .container {
            max-width: 600px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin: 20px;
            text-align: center;
            animation: fadeIn 1.5s ease-in-out; /* Fade-in effect */
        }

        /* Fade-in animation for the container */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Website Name Styling with Animation */
        .website-name {
            font-size: 28px;
            font-weight: bold;
            color: #f1c40f; /* Golden color */
            margin-bottom: 20px;
            text-transform: uppercase;
            animation: slideIn 1s ease-in-out; /* Slide-in animation */
        }

        /* Slide-in animation for the website name */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            color: #495057;
        }
        input[type="file"] {
            padding: 8px;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .info {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Website Name as Header with Animation -->
        <div class="website-name">Book Corner</div>
        
        <h1>Submit Rare Book Details</h1>
        
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to submit?')">
            <div class="form-group">
                <label for="product_name">Book Name <span class="info">(e.g., Title of the Book)</span></label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="farmer_phone">Seller Phone</label>
                <input type="text" id="farmer_phone" name="farmer_phone" value="<?php echo $sessphonenumber; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="product_image">Book Image <span class="info">(Upload an image in JPG, PNG format)</span></label>
                <input type="file" id="product_image" name="product_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="product_description">Book Amount and Description</label>
                <textarea id="product_description" name="product_description" rows="4" required placeholder="Enter the amount and a brief description..."></textarea>
            </div>

            <div class="form-group">
                <label for="lowest_bid">Lowest Bid (USD)</label>
                <input type="number" id="lowest_bid" name="lowest_bid" min="1" required placeholder="Enter minimum bid amount">
            </div>

            <div class="form-group">
                <label for="bid_ending_time">Bid Ending Time</label>
                <input type="datetime-local" id="bid_ending_time" name="bid_ending_time" required>
            </div>

            <button type="submit" name="insert_pro">Submit Book for Bidding</button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['insert_pro'])) {    // when button is clicked

    // getting the text data from fields
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $lowest_bid = $_POST['lowest_bid'];
    $bid_ending_time = $_POST['bid_ending_time'];
    $farmer_phone = $_POST['farmer_phone'];

    // getting image
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp = $_FILES['product_image']['tmp_name'];  // for server

    if (isset($_SESSION['phonenumber'])) {
        move_uploaded_file($product_image_tmp, "../Admin/product_images/$product_image");

        $phone = $_SESSION['phonenumber'];
        $getting_id = "select * from farmerregistration where farmer_phone = $sessphonenumber";
        $run = mysqli_query($con, $getting_id);
        $row = mysqli_fetch_array($run);
        $id = $row['farmer_id'];
        
        // Insert product details into database
        $insert_product = "insert into bid (product_name, product_description, product_image, lowest_bid, bid_ending_time, farmer_phone) 
                           values ('$product_name','$product_description','$product_image','$lowest_bid','$bid_ending_time', '$farmer_phone')";

        $insert_query = mysqli_query($con, $insert_product);

        if ($insert_query) {
            echo "<script>alert('Product has been added successfully.')</script>";
            echo "<script>window.open('farmerHomepage.php','_self')</script>";
        } else {
            echo "<script>alert('Error uploading data. Please check your connection and try again.')</script>";
        }
    }
}
?>
