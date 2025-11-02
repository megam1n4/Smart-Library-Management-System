<?php
// Include your database connection code
include("../Includes/db.php");

// Start the session
session_start();

if (isset($_POST['login'])) {
    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Authentication code (assuming you have this set up correctly)
    if ($count_rows == 1) {
        $_SESSION['phonenumber'] = $phonenumber;
    } else {
        echo "<script>alert('Please Enter Valid Details');</script>";
        echo "<script>window.open('FarmerLogin.php','_self')</script>";
    }
}

if (isset($_SESSION['phonenumber'])) {
    $auth_phonenumber = $_SESSION['phonenumber'];
    $query = "SELECT b.bid_id, b.product_id, b.bid_amount, b.farmer_phone, b.buyer_address
              FROM bids AS b
              WHERE b.farmer_phone = '$auth_phonenumber'";
    $result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bidders</title>
    <style>
        /* Background Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        /* Container Styling */
        .container {
            max-width: 800px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin: 20px;
        }

        /* Heading Styling */
        h1 {
            text-align: center;
            color: #4e54c8;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #4e54c8;
            color: #ffffff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }

        /* No Bidders Message */
        .no-bidders {
            text-align: center;
            font-size: 18px;
            color: #666;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Book Bidders</h1>
        <table>
            <tr>
                <th>Bid ID</th>
                <th>Book ID</th>
                <th>Bid Amount</th>
                <th>Seller Phone</th>
                <th>Buyer Address</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['bid_id']}</td>
                        <td>{$row['product_id']}</td>
                        <td>\${$row['bid_amount']}</td>
                        <td>{$row['farmer_phone']}</td>
                        <td>{$row['buyer_address']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-bidders'>No bidders found for your products.</td></tr>";
            }

            // Close the database connection
            mysqli_close($con);
            ?>
        </table>
    </div>
</body>
</html>
<?php
} // Close the if(isset($_SESSION['phonenumber'])) condition
?>
