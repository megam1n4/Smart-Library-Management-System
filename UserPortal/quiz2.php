<?php
include("../Functions/functions.php");
include("../Includes/db.php");

if (!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('You must be logged in to take the quiz.');</script>";
    echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
    exit();
}

$phonenumber = $_SESSION['phonenumber'];

// Fetch buyer's name based on phone number
$name_query = "SELECT buyer_name FROM userregistration WHERE buyer_phone = '$phonenumber'";
$name_result = mysqli_query($con, $name_query);
$buyer_data = mysqli_fetch_assoc($name_result);
$buyer_name = $buyer_data['buyer_name'] ?? 'Unknown User';

if (!isset($_SESSION['quiz2_start_time'])) {
    $_SESSION['quiz2_start_time'] = time(); // Record the start time in session
}

// Define SQL quiz questions
$sql_questions = [
    [
        'id' => 1,
        'question' => 'Which SQL statement is used to extract data from a database?',
        'option_a' => 'GET',
        'option_b' => 'SELECT',
        'option_c' => 'EXTRACT',
        'option_d' => 'OPEN',
        'correct_option' => 'B'
    ],
    [
        'id' => 2,
        'question' => 'Which SQL keyword is used to sort the result-set?',
        'option_a' => 'SORT BY',
        'option_b' => 'ARRANGE',
        'option_c' => 'ORDER BY',
        'option_d' => 'SORT',
        'correct_option' => 'C'
    ],
    [
        'id' => 3,
        'question' => 'What does the SQL COUNT() function do?',
        'option_a' => 'Adds all values in a column',
        'option_b' => 'Returns the number of rows',
        'option_c' => 'Multiplies column values',
        'option_d' => 'Divides column values',
        'correct_option' => 'B'
    ],
    [
        'id' => 4,
        'question' => 'Which JOIN returns all records when there is a match in either left or right table?',
        'option_a' => 'INNER JOIN',
        'option_b' => 'LEFT JOIN',
        'option_c' => 'RIGHT JOIN',
        'option_d' => 'FULL OUTER JOIN',
        'correct_option' => 'D'
    ],
    [
        'id' => 5,
        'question' => 'What is the correct SQL syntax to delete all records from a table named "users"?',
        'option_a' => 'DELETE FROM users',
        'option_b' => 'REMOVE * FROM users',
        'option_c' => 'DROP TABLE users',
        'option_d' => 'DELETE * FROM users',
        'correct_option' => 'A'
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $start_time = $_SESSION['quiz2_start_time'];
    $end_time = time();
    $time_taken = $end_time - $start_time; // Time taken in seconds
    
    // Loop through questions and check answers
    foreach ($_POST['answers'] as $question_id => $selected_option) {
        foreach ($sql_questions as $question) {
            if ($question['id'] == $question_id && $selected_option === $question['correct_option']) {
                $score++;
                break;
            }
        }
    }

    // Insert result into `quiz_results` table (reusing the same table with a different identifier)
    $quiz_type = 'SQL Quiz';
    $insert_query = "INSERT INTO quiz_results (buyer_phone, buyer_name, score, time_taken) VALUES ('$phonenumber', '$buyer_name ($quiz_type)', '$score', '$time_taken')";
    mysqli_query($con, $insert_query);

    // Clear the start time from session
    unset($_SESSION['quiz2_start_time']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Quiz - Smart Library</title>

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

        /* Quiz Header */
        .quiz-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .quiz-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .quiz-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Score Alert */
        .score-alert {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
            margin-bottom: 40px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .score-alert h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .score-alert p {
            font-size: 1.3rem;
            margin: 0;
        }

        .retry-btn {
            background: white;
            color: #28a745;
            padding: 12px 40px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Quiz Container */
        .quiz-container {
            max-width: 900px;
            margin: 0 auto 50px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .quiz-container h2 {
            color: #1a1a2e;
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2rem;
        }

        /* Question Card */
        .question-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .question-card p {
            font-weight: 700;
            font-size: 1.15rem;
            color: #1a1a2e;
            margin-bottom: 20px;
        }

        /* Radio Options */
        .form-check {
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .form-check:hover {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-check input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-check label {
            font-size: 1rem;
            margin-left: 12px;
            cursor: pointer;
            color: #333;
        }

        /* Submit Button */
        .submit-quiz-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-quiz-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
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
            .quiz-header h1 {
                font-size: 2rem;
            }

            .quiz-container {
                padding: 20px;
                margin: 0 15px 30px 15px;
            }

            .question-card {
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
                        echo "<a href='quizgenre.php' class='list-group-item list-group-item-action'>Join Quiz</a>";
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
                                
                                echo "<a href='quizgenre.php' class='dropdown-item'>Join Quiz</a>";
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

    <!-- Quiz Header -->
    <div class="quiz-header">
        <h1>💻 SQL Quiz</h1>
        <p>Test your knowledge of SQL and database queries</p>
    </div>

    <!-- Quiz Content -->
    <div class="container">
        <?php if (isset($score)): ?>
            <!-- Score Display -->
            <div class="score-alert">
                <h2>🎉 Quiz Completed!</h2>
                <p>You scored <strong><?php echo $score; ?></strong> out of <strong>5</strong></p>
                <p>Time taken: <strong><?php echo gmdate("i:s", $time_taken); ?></strong> minutes</p>
                <button class="retry-btn" onclick="window.location.href='quiz2.php'">
                    <i class="fas fa-redo"></i> Try Again
                </button>
            </div>
        <?php else: ?>
            <!-- Quiz Form -->
            <div class="quiz-container">
                <h2>Answer the following questions:</h2>
                <form action="quiz2.php" method="POST">
                    <?php 
                    $question_num = 1;
                    foreach ($sql_questions as $question): 
                    ?>
                        <div class="question-card">
                            <p><strong>Question <?php echo $question_num; ?>:</strong> <?php echo htmlspecialchars($question['question']); ?></p>
                            <div class="form-check">
                                <input type="radio" id="q<?php echo $question['id']; ?>_a" name="answers[<?php echo $question['id']; ?>]" value="A" required>
                                <label for="q<?php echo $question['id']; ?>_a"><?php echo htmlspecialchars($question['option_a']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="q<?php echo $question['id']; ?>_b" name="answers[<?php echo $question['id']; ?>]" value="B">
                                <label for="q<?php echo $question['id']; ?>_b"><?php echo htmlspecialchars($question['option_b']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="q<?php echo $question['id']; ?>_c" name="answers[<?php echo $question['id']; ?>]" value="C">
                                <label for="q<?php echo $question['id']; ?>_c"><?php echo htmlspecialchars($question['option_c']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="q<?php echo $question['id']; ?>_d" name="answers[<?php echo $question['id']; ?>]" value="D">
                                <label for="q<?php echo $question['id']; ?>_d"><?php echo htmlspecialchars($question['option_d']); ?></label>
                            </div>
                        </div>
                    <?php 
                    $question_num++;
                    endforeach; 
                    ?>
                    <button type="submit" class="submit-quiz-btn">
                        <i class="fas fa-check-circle"></i> Submit Quiz
                    </button>
                </form>
            </div>
        <?php endif; ?>
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

</body>

</html>