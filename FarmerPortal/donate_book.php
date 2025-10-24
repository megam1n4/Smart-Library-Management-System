<?php
session_start();
include("../Includes/db.php");

// Check if the farmer is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to donate books.";
    exit();
}

$farmer_phone = $_SESSION['phonenumber'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_title = mysqli_real_escape_string($con, $_POST['book_title']);
    $book_description = mysqli_real_escape_string($con, $_POST['book_description']);
    $condition = mysqli_real_escape_string($con, $_POST['condition']);
    $image_path = null;

    // Handle image upload
    if (!empty($_FILES['book_image']['name'])) {
        $image_name = $_FILES['book_image']['name'];
        $image_tmp = $_FILES['book_image']['tmp_name'];
        $upload_dir = 'uploads/';

        // Create upload directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_path = $upload_dir . basename($image_name);
        move_uploaded_file($image_tmp, $image_path);
    }

    // Insert into the book_donations table
    $query = "INSERT INTO book_donations (farmer_phone, book_title, book_description, `condition`, image_path) 
              VALUES ('$farmer_phone', '$book_title', '$book_description', '$condition', '$image_path')";
    $result = mysqli_query($con, $query);

    if ($result) {
        $message = "<p class='alert alert-success'>Book donated successfully!</p>";
    } else {
        $message = "<p class='alert alert-danger'>Error: Could not donate the book.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate a Book</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Donate a Book</h2>
    <?php if (isset($message)) echo $message; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="book_title">Book Title:</label>
            <input type="text" name="book_title" id="book_title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="book_description">Book Description:</label>
            <textarea name="book_description" id="book_description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="condition">Condition:</label>
            <select name="condition" id="condition" class="form-control" required>
                <option value="New">New</option>
                <option value="Used - Good">Used - Good</option>
                <option value="Used - Acceptable">Used - Acceptable</option>
            </select>
        </div>
        <div class="form-group">
            <label for="book_image">Upload Image (optional):</label>
            <input type="file" name="book_image" id="book_image" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Donate Book</button>
    </form>
</div>

</body>
</html>
