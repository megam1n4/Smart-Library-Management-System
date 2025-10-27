<?php
session_start();
include("../Includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to view and place bids.";
    exit();
}

if (isset($_POST['submit_bid'])) {
    $product_id = $_POST['product_id'];
    $bid_amount = $_POST['bid_amount'];
    $farmer_phone = $_POST['farmer_phone'];
    $buyer_address = $_POST['buyer_address'];

    // Insert the bid into the 'bids' table
    $query = "INSERT INTO bids (product_id, bid_amount, farmer_phone, buyer_address) VALUES ('$product_id', '$bid_amount', '$farmer_phone', '$buyer_address')";
    $result = mysqli_query($con, $query);
    $message = $result ? "<p class='alert alert-success'>Bid placed successfully.</p>" : "<p class='alert alert-danger'>Failed to place the bid.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rare Book Bidding</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Basic Reset and Background */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: url('test3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }

        /* Overlay for dark background effect */
        .overlay {
            background: rgba(0, 0, 0, 0.7);
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: -1;
        }

        /* Container Styling */
        .container {
            max-width: 900px;
            margin: 60px auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        /* Title */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: bold;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #222;
            padding: 15px 10px;
        }

        .navbar-brand {
            font-weight: bold;
            color: #ffdd57 !important;
            font-size: 24px;
        }

        .navbar-nav .nav-link {
            color: #ddd !important;
            font-weight: 500;
            font-size: 18px;
            margin-right: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            margin-top: 20px;
        }

        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            text-align: center;
        }

        td {
            text-align: center;
            padding: 12px;
            color: #333;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        /* Form Input Styling */
        .bid-input, .contact-input {
            max-width: 100px;
            display: inline-block;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: transform 0.3s, background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Alert Message Styling */
        .alert {
            text-align: center;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        /* Navbar Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 20px;
            }
            .navbar-nav .nav-link {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<!-- Background Overlay -->
<div class="overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">
        <i class="fas fa-book-reader mr-2"></i>Book Corner
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Browse Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Container -->
<div class="container">
    <h1>Rare Book Details</h1>

    <!-- Display message -->
    <?php if (isset($message)) echo $message; ?>

    <!-- Book details table -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Book Name</th>
                <th>Description</th>
                <th>Lowest Bid</th>
                <th>Bid Ending Time</th>
                <th>Seller Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php
        // Query to fetch data from the 'bid' table
        $query = "SELECT * FROM bid";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['product_name']) . "</td>
                    <td>" . htmlspecialchars($row['product_description']) . "</td>
                    <td>$" . htmlspecialchars($row['lowest_bid']) . "</td>
                    <td>" . htmlspecialchars($row['bid_ending_time']) . "</td>
                    <td>" . htmlspecialchars($row['farmer_phone']) . "</td>
                    <td>
                        <form class='form-inline' method='post' action=''>
                            <input class='form-control bid-input' type='number' name='bid_amount' placeholder='Bid Amount' min='{$row['lowest_bid']}' required>
                            <input class='form-control contact-input' type='text' name='buyer_address' placeholder='Contact No' required>
                            <input type='hidden' name='product_id' value='{$row['product_id']}'>
                            <input type='hidden' name='farmer_phone' value='{$row['farmer_phone']}'>
                            <button type='submit' name='submit_bid' class='btn btn-primary'>Place Bid</button>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No records found in the 'bid' table.</td></tr>";
        }

        mysqli_close($con);
        ?>

        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
