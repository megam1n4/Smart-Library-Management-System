<?php
include("../Functions/functions.php");
include("../Includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('You must be logged in to participate in the book discussion.');</script>";
    echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Get the buyer's phone number

// Handle new message and file upload submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = isset($_POST['message']) ? mysqli_real_escape_string($con, $_POST['message']) : '';
    $file_path = null;
    $file_type = null;

    // Check if a file was uploaded
    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_dir = 'uploads/';

        // Create upload directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Define the file path and move the uploaded file
        $file_path = $upload_dir . basename($file_name);
        move_uploaded_file($file_tmp, $file_path);

        // Set file type for images and other files
        $file_type = (strpos(mime_content_type($file_path), 'image') === 0) ? 'image' : 'file';
    }

    // Insert the message or file information into the database
    $query = "INSERT INTO book_discussion_chat (buyer_phone, message, file_path, file_type) 
              VALUES ('$phonenumber', '$message', '$file_path', '$file_type')";
    mysqli_query($con, $query);
}

// Fetch all messages and files along with the buyer's name
$chat_query = "
    SELECT c.message, c.timestamp, c.file_path, c.file_type, IF(b.buyer_phone = '$phonenumber', 'You', b.buyer_name) AS name 
    FROM book_discussion_chat c
    JOIN userregistration b ON c.buyer_phone = b.buyer_phone
    ORDER BY c.timestamp ASC";
$chat_result = mysqli_query($con, $chat_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Discussion Chat - Smart Library</title>

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

        /* Chat Header */
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .chat-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .chat-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Chat Container */
        .chat-container {
            max-width: 900px;
            margin: 0 auto 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Chat Box */
        #chat-box {
            height: 500px;
            overflow-y: auto;
            padding: 30px;
            background: #f8f9fa;
        }

        /* Chat Message Styling */
        .chat-message {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 18px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .my-message {
            background: linear-gradient(135deg, #d1f5d3 0%, #c8f0ca 100%);
            color: #1a1a2e;
            margin-left: auto;
            border-radius: 18px 18px 4px 18px;
        }

        .other-message {
            background: white;
            color: #333;
            margin-right: auto;
            border-radius: 18px 18px 18px 4px;
            border: 1px solid #e9ecef;
        }

        .chat-message strong {
            color: #667eea;
            font-weight: 700;
        }

        .chat-message p {
            margin: 5px 0;
            line-height: 1.5;
        }

        .chat-time {
            font-size: 0.75rem;
            color: #6c757d;
            display: block;
            margin-top: 5px;
        }

        /* Image Styling */
        .chat-image {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .chat-image:hover {
            transform: scale(1.02);
        }

        /* File Link */
        .chat-message a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .chat-message a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Message Form */
        .message-form {
            padding: 25px;
            background: white;
            border-top: 2px solid #e9ecef;
        }

        .message-form textarea {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            resize: none;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .message-form textarea:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .file-input-wrapper {
            margin: 15px 0;
        }

        .file-input-label {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            display: block;
        }

        .custom-file-input {
            cursor: pointer;
        }

        .send-button {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            float: right;
        }

        .send-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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

        /* Scrollbar Styling */
        #chat-box::-webkit-scrollbar {
            width: 8px;
        }

        #chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #chat-box::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 10px;
        }

        #chat-box::-webkit-scrollbar-thumb:hover {
            background: #218838;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-header h1 {
                font-size: 2rem;
            }

            .chat-container {
                margin: 0 15px 30px 15px;
            }

            #chat-box {
                height: 400px;
                padding: 20px 15px;
            }

            .chat-message {
                max-width: 85%;
            }

            .message-form {
                padding: 15px;
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

    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="bhome.php">
                <img src="logo2.jpg" alt="Smart Library Logo">
            </a>

            <!-- Mobile Icons -->
            <div class="d-xl-none" style="display: flex; align-items: center; gap: 15px;">
                <i class='far fa-user-circle user-icon'></i>
                <a href="CartPage.php" style="position: relative;">
                    <i class="fa fa-shopping-cart cart-icon"></i>
                    <span id="icon"><?php echo totalItems(); ?></span>
                </a>
            </div>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-bars" style="color:#28a745; font-size:28px;"></i>
            </button>

            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Search Box -->
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

                <!-- Username Display -->
                <?php getUsername(); ?>

                <!-- Mobile Menu List -->
                <div class="list-group moblists">
                    <?php
                    if (isset($_SESSION['phonenumber'])) {
                        echo "<a href='BuyerProfile.php' class='list-group-item list-group-item-action'>Profile</a>";
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

                <!-- Desktop Right Icons -->
                <div class="ml-auto d-none d-xl-flex" style="display: flex; align-items: center; gap: 20px;">
                    <!-- Voice Search -->
                    <a href="voice_search.php" class="voice-search-btn">
                        <i class="fas fa-microphone"></i> Voice Search
                    </a>

                    <!-- Explore Dropdown -->
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
                    <!-- User Icon -->
                    <i class='far fa-user-circle user-icon'></i>

                    <!-- Cart Icon -->
                    <a href="CartPage.php" style="position: relative;">
                        <i class="fa fa-shopping-cart cart-icon"></i>
                        <span id="icon"><?php echo totalItems(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Chat Header -->
    <div class="chat-header">
        <h1>💬 Book Discussion Chat</h1>
        <p>Share your thoughts and connect with fellow book lovers</p>
    </div>

    <!-- Chat Container -->
    <div class="container">
        <div class="chat-container">
            <!-- Chat Box -->
            <div id="chat-box">
                <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
                    <div class="chat-message <?php echo ($row['name'] == 'You') ? 'my-message' : 'other-message'; ?>">
                        <strong><?php echo htmlspecialchars($row['name']); ?>:</strong>

                        <!-- Display message text if available -->
                        <?php if (!empty($row['message'])): ?>
                            <p><?php echo htmlspecialchars($row['message']); ?></p>
                        <?php endif; ?>

                        <!-- Display image if file_type is 'image' -->
                        <?php if ($row['file_type'] === 'image'): ?>
                            <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="Image" class="chat-image">
                        <?php endif; ?>

                        <!-- Display download link if file_type is 'file' -->
                        <?php if ($row['file_type'] === 'file'): ?>
                            <p><a href="<?php echo htmlspecialchars($row['file_path']); ?>" download><i class="fas fa-download"></i> Download File</a></p>
                        <?php endif; ?>

                        <span class="chat-time"><?php echo date("F j, Y, g:i a", strtotime($row['timestamp'])); ?></span>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Message Input Form -->
            <form action="chat.php" method="POST" enctype="multipart/form-data" class="message-form">
                <div class="form-group">
                    <textarea name="message" class="form-control" placeholder="Type your message here..." rows="3"></textarea>
                </div>
                <div class="file-input-wrapper">
                    <label class="file-input-label"><i class="fas fa-paperclip"></i> Attach a file (optional):</label>
                    <input type="file" name="file" class="form-control-file">
                </div>
                <button type="submit" class="btn send-button">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>

    <!-- Footer -->
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

    <!-- Auto-Scroll to Bottom of Chat -->
    <script>
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    </script>

</body>

</html>