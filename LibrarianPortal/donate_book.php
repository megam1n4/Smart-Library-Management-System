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
        // Note: The move_uploaded_file function is crucial for file storage
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* --- Global Body Styling --- */
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Minimal Header/Logo Bar Styling (From Leaderboard) --- */
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

        /* --- Form Container Styling (Modernized) --- */
        .container {
            max-width: 600px;
            margin: 50px auto; 
            padding: 40px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Allows container to fill space */
        }
        
        .container h2 {
            font-weight: 800;
            color: #292b2c;
            font-size: 2rem;
            margin-bottom: 30px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            text-align: center;
        }
        
        .form-group label {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control, .form-control-file {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus, .form-control-file:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 8px;
            font-weight: 600;
        }

        /* --- Footer Styling (From Leaderboard) --- */
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

        .social li a {
            color: #ffc107;
            font-size: 24px;
            transition: all 0.3s ease;
        }
        
        .social li a:hover {
            color: #28a745;
        }
    </style>
</head>
<body>

    <div class="top-logo-bar">
        <a href="LibrarianHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo">
        </a>
    </div>

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
                    <p class="h6"><a class="text-green ml-2" target="_blank">Foreign Key Friends</a></p>
                </div>
            </div>
        </div>
    </section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>