<?php
session_start();
include("../Includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to view and claim books.";
    exit();
}

$buyer_phone = $_SESSION['phonenumber'];

// Handle book claim
if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']); // Get book ID from the URL

    // Check if the book is already claimed by the user
    $check_query = "SELECT * FROM book_claims WHERE buyer_phone = '$buyer_phone' AND book_id = $book_id";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<div class='alert alert-danger text-center'>You have already claimed this book.</div>";
    } else {
        // Insert claim into the database
        $claim_query = "INSERT INTO book_claims (buyer_phone, book_id) VALUES ('$buyer_phone', $book_id)";
        $claim_result = mysqli_query($con, $claim_query);

        if ($claim_result) {
            echo "<div class='alert alert-success text-center'>Book claimed successfully!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Failed to claim the book. Please try again.</div>";
        }
    }
}

// Fetch all books available for donation
$query = "SELECT * FROM book_donations ORDER BY created_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Books</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('test3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background: rgba(50, 50, 50, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
        }
        h2 {
            color: #ff5733;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7);
            font-weight: bold;
        }
        .card {
            margin-bottom: 20px;
            overflow: hidden;
            transform: scale(1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
            border: 1px solid #ff5733;
        }
        .card:hover {
            transform: scale(1.08);
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.7);
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover img {
            transform: scale(1.2);
        }
        .btn {
            background-color: #c70039;
            border: none;
            font-weight: bold;
            color: #fff;
        }
        .btn:hover {
            background-color: #900c3f;
            transform: translateY(-2px);
        }
        .card-title {
            color: #ffc300;
            font-weight: bold;
        }
        .card-text {
            color: #000;
        }
        .text-muted {
            color: #f39c12 !important;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4"><i class="fas fa-book"></i> Get Free Books</h2>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if ($row['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="Book Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-bookmark"></i> <?php echo htmlspecialchars($row['book_title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['book_description']); ?></p>
                        <p class="text-muted"><i class="fas fa-tag"></i> Condition: <?php echo htmlspecialchars($row['condition']); ?></p>
                        <a href="claimbook.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-block">
                            <i class="fas fa-hand-holding-heart"></i> Claim This Book
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
