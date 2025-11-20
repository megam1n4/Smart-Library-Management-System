<?php
session_start();
// Removed: include("../Functions/functions.php"); to avoid duplicate session_start() call
include("../Includes/db.php");

// Check if the farmer is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('You must be logged in to donate books.');</script>";
    echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
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
        $message = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Book donated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> Error: Could not donate the book.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate a Book - Smart Library</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        /* Modern Navbar Styling */
        nav.navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            object-fit: contain;
            background: white;
            padding: 5px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        /* Search Box Styling */
        .searchbox .input-group {
            max-width: 600px;
            margin: 0 auto;
        }

        .searchbox .input-group-text {
            background-color: white;
            border: 2px solid #28a745;
            border-right: none;
            border-radius: 25px 0 0 25px;
            color: #28a745;
        }

        .searchbox .form-control {
            border: 2px solid #28a745;
            border-left: none;
            border-radius: 0 25px 25px 0;
            padding: 10px 20px;
        }

        .searchbox .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            border-color: #28a745;
        }

        /* User Icons */
        .user-icon,
        .cart-icon {
            color: #28a745;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-icon:hover,
        .cart-icon:hover {
            color: #20c997;
            transform: scale(1.1);
        }

        #icon {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* Dropdown Button */
        .btn-success {
            background: #28a745;
            border: none;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Voice Search Button */
        .voice-search-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .voice-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Donation Header */
        .donation-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .donation-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .donation-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Donation Container */
        .donation-container {
            max-width: 800px;
            margin: 0 auto 50px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }

        .form-group label i {
            color: #28a745;
            font-size: 1.1rem;
        }

        .form-control,
        select.form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        select.form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* File Upload Styling */
        .custom-file-upload {
            border: 2px dashed #28a745;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .custom-file-upload:hover {
            background: #e9f7ef;
            border-color: #20c997;
        }

        .custom-file-upload i {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 10px;
        }

        .custom-file-upload p {
            margin: 10px 0 0 0;
            color: #666;
            font-size: 0.95rem;
        }

        input[type="file"] {
            display: none;
        }

        .file-name {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #28a745;
            font-weight: 600;
        }

        /* Submit Button */
        .btn-donate {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 50px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-donate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        /* Alert Messages */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Footer */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: 80px;
        }

        .social li {
            margin: 0 10px;
        }

        .social a {
            color: #ffc107;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .social a:hover {
            color: #28a745;
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .donation-header h1 {
                font-size: 2rem;
            }

            .donation-container {
                padding: 25px;
                margin: 0 15px 30px 15px;
            }

            .btn-donate {
                padding: 12px 30px;
                font-size: 1.1rem;
            }
        }

        /* Mobile List Styling */
        .moblists {
            display: none;
        }

        @media (max-width: 1200px) {
            .moblists {
                display: block;
                margin-top: 20px;
            }

            .moblists .list-group-item {
                background-color: #1a1a2e !important;
                color: #ffc107 !important;
                border: none;
                text-align: center;
                padding: 15px;
                transition: all 0.3s ease;
            }

            .moblists .list-group-item:hover {
                background-color: #28a745 !important;
                color: white !important;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="bhome.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>

            <div class="d-xl-none" style="display: flex; align-items: center; gap: 15px;">
                <i class='far fa-user-circle user-icon'></i>
                <a href="CartPage.php" style="position: relative;">
                    <i class="fa fa-shopping-cart cart-icon"></i>
                    <span id="icon">0</span> 
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-bars" style="color:#28a745; font-size:28px;"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="mx-auto searchbox">
                    <form action="SearchResult.php" method="get" enctype="multipart/form-data">
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search" style="font-size:20px;"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="search" placeholder="Search for education, romance books" style="width:500px;">
                        </div>
                    </form>
                </div>

                <div class="list-group moblists">
                    <?php
                    // Links assume the user is a Buyer, based on original file context
                    if (isset($_SESSION['phonenumber'])) {
                        echo "<a href='UserProfile.php' class='list-group-item list-group-item-action'>Profile</a>";
                        echo "<a href='Transaction.php' class='list-group-item list-group-item-action'>Transactions</a>";
                        echo "<a href='claimbook.php' class='list-group-item list-group-item-action'>Claim Book</a>";
                        echo "<a href='rarereserve.php' class='list-group-item list-group-item-action'>Bid Rare Book</a>";
                        echo "<a href='chat.php' class='list-group-item list-group-item-action'>Group Chat</a>";
                        echo "<a href='debate.php' class='list-group-item list-group-item-action'>Join Debate</a>";
                        echo "<a href='genre.php' class='list-group-item list-group-item-action'>Join Quiz</a>";
                        echo "<a href='customersupport.php' class='list-group-item list-group-item-action'>Join Meet & Greet</a>";
                        echo "<a href='../Includes/logout.php' class='list-group-item list-group-item-action'>Logout</a>";
                    } else {
                        echo "<a href='../auth/UserLogin.php' class='list-group-item list-group-item-action'>Login</a>";
                    }
                    ?>
                </div>

                <div class="ml-auto d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px;">
                    <a href="voice_search.php" class="voice-search-btn">
                        <i class="fas fa-microphone"></i> Voice Search
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Explore
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            if (isset($_SESSION['phonenumber'])) {
                                echo "<a href='UserProfile.php' class='dropdown-item'>Profile</a>";
                                
                                echo "<a href='rarereserve.php' class='dropdown-item'>Reserve Rare Book</a>";
                                echo "<a href='chat.php' class='dropdown-item'>Group chat</a>";
                                
                                echo "<a href='genre.php' class='dropdown-item'>Join Quiz</a>";
                                echo "<a href='Donate.php' class='dropdown-item'>Book Donation</a>";
                                echo "<a href='exhibition.php' class='dropdown-item'>Join Rare Book Exhibition</a>";
                                echo "<a href='../Includes/logout.php' class='dropdown-item'>Logout</a>";
                            } else {
                                echo "<a href='../auth/UserLogin.php' class='dropdown-item'>Login</a>";
                            }
                            ?>
                        </div>
                    </div>

                    <i class='far fa-user-circle user-icon'></i>

                    <a href="CartPage.php" style="position: relative;">
                        <i class="fa fa-shopping-cart cart-icon"></i>
                        <span id="icon">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="donation-header">
        <h1>📚 Donate a Book</h1>
        <p>Share knowledge and help others discover great books</p>
    </div>

    <div class="container">
        <div class="donation-container">
            <?php if (isset($message)) echo $message; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="book_title">
                        <i class="fas fa-book"></i>
                        Book Title
                    </label>
                    <input type="text" name="book_title" id="book_title" class="form-control" placeholder="Enter book title" required>
                </div>

                <div class="form-group">
                    <label for="book_description">
                        <i class="fas fa-align-left"></i>
                        Book Description
                    </label>
                    <textarea name="book_description" id="book_description" class="form-control" placeholder="Provide a brief description of the book" required></textarea>
                </div>

                <div class="form-group">
                    <label for="condition">
                        <i class="fas fa-star"></i>
                        Condition
                    </label>
                    <select name="condition" id="condition" class="form-control" required>
                        <option value="New">New</option>
                        <option value="Used - Good">Used - Good</option>
                        <option value="Used - Acceptable">Used - Acceptable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-image"></i>
                        Upload Book Image (Optional)
                    </label>
                    <label for="book_image" class="custom-file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><strong>Click to upload</strong> or drag and drop</p>
                        <p>PNG, JPG up to 10MB</p>
                    </label>
                    <input type="file" name="book_image" id="book_image" accept="image/*" onchange="displayFileName()">
                    <div id="file-name" class="file-name"></div>
                </div>

                <button type="submit" class="btn-donate">
                    <i class="fas fa-heart"></i> Donate Book
                </button>
            </form>
        </div>
    </div>

    <section id="footer" class="myfooter">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <ul class="list-unstyled list-inline social text-center">
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-google-plus"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2 text-center">
                    <p><strong>Smart Library Management System</strong> - An Advanced Digital Library Management System</p>
                    <p>Copyright © All Rights Reserved. Foreign Key Friends</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        function displayFileName() {
            const input = document.getElementById('book_image');
            const fileNameDiv = document.getElementById('file-name');
            
            if (input.files && input.files[0]) {
                fileNameDiv.textContent = '✓ ' + input.files[0].name;
            } else {
                fileNameDiv.textContent = '';
            }
        }
    </script>

</body>

</html>