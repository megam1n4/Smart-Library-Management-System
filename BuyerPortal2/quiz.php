<?php
include("../Functions/functions.php");
include("../Includes/db.php");

// Start quiz timer
if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

// Fetch buyer information
$phonenumber = $_SESSION['phonenumber'];
$sql = "SELECT * FROM buyerregistration WHERE buyerphone = '$phonenumber'";
$run_query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($run_query)) {
    $buyer_name = $row['buyername'];
}

// Fetch quiz questions
$quiz_query = "SELECT * FROM quiz_questions ORDER BY RAND() LIMIT 10";
$quiz_result = mysqli_query($con, $quiz_query);

// Handle form submission
if (isset($_POST['submit_quiz'])) {
    $score = 0;
    $time_taken = time() - $_SESSION['quiz_start_time'];
    
    foreach ($_POST['question'] as $question_id => $selected_option) {
        $question_query = "SELECT correct_option FROM quiz_questions WHERE id = '$question_id'";
        $question_result = mysqli_query($con, $question_query);
        $question = mysqli_fetch_assoc($question_result);
        
        if ($selected_option === $question['correct_option']) {
            $score++;
        }
    }
    
    // Insert result into quiz_results table
    $insert_query = "INSERT INTO quiz_results (buyer_phone, buyer_name, score, time_taken) 
                     VALUES ('$phonenumber', '$buyer_name', '$score', '$time_taken')";
    mysqli_query($con, $insert_query);
    
    // Clear the start time from session
    unset($_SESSION['quiz_start_time']);
    
    // Redirect to results page or show success message
    echo "<script>alert('Quiz submitted successfully! Your score: $score/10');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Quiz - Smart Library</title>
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
            margin-right: 10px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffd700 !important;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #ffd700 !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #ffd700;
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar-toggler {
            border: none;
            padding: 5px 10px;
        }

        .navbar-toggler-icon {
            color: white;
            font-size: 1.5rem;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-icons a {
            color: #ffffff;
            font-size: 1.3rem;
            position: relative;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-icons a:hover {
            color: #ffd700;
            transform: scale(1.1);
        }

        .icon-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Quiz Container */
        .quiz-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }

        .quiz-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .quiz-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .quiz-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .question-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .question-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .option-label {
            display: block;
            padding: 15px 20px;
            margin-bottom: 12px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .option-label:hover {
            background: #e7f3ff;
            border-color: #667eea;
            transform: translateX(5px);
        }

        input[type="radio"]:checked + .option-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            font-weight: 600;
        }

        input[type="radio"] {
            display: none;
        }

        .submit-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 50px;
            font-size: 1.2rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
            display: block;
            margin: 30px auto;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(40, 167, 69, 0.4);
        }

        /* Footer Styling */
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: 80px;
        }

        .footer-content {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer-content h3 {
            color: #ffd700;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer-content p {
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            color: #ffd700;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quiz-header h1 {
                font-size: 1.8rem;
            }

            .question-card {
                padding: 20px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .quiz-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="bhome.php">
            <i class="fas fa-book-reader"></i> Smart Library Management System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="bhome.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Categories.php">Browse Books</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="quiz.php">Quiz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>
            <div class="nav-icons ml-3">
                <a href="UserProfile.php" title="My Profile">
                    <i class="fas fa-user-circle"></i>
                </a>
                <a href="cartpage.php" title="My Cart">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Quiz Content -->
    <div class="quiz-container">
        <div class="quiz-header">
            <h1><i class="fas fa-graduation-cap"></i> Literature Quiz</h1>
            <p>Test your knowledge of classic literature and famous authors</p>
        </div>

        <form method="POST" action="">
            <?php
            $question_number = 1;
            while ($quiz_row = mysqli_fetch_assoc($quiz_result)) {
                ?>
                <div class="question-card">
                    <div class="question-title">
                        <strong>Question <?php echo $question_number; ?>:</strong> 
                        <?php echo $quiz_row['question']; ?>
                    </div>
                    <div class="options">
                        <input type="radio" name="question[<?php echo $quiz_row['id']; ?>]" 
                               value="A" id="q<?php echo $quiz_row['id']; ?>_a" required>
                        <label class="option-label" for="q<?php echo $quiz_row['id']; ?>_a">
                            <?php echo $quiz_row['option_a']; ?>
                        </label>

                        <input type="radio" name="question[<?php echo $quiz_row['id']; ?>]" 
                               value="B" id="q<?php echo $quiz_row['id']; ?>_b">
                        <label class="option-label" for="q<?php echo $quiz_row['id']; ?>_b">
                            <?php echo $quiz_row['option_b']; ?>
                        </label>

                        <input type="radio" name="question[<?php echo $quiz_row['id']; ?>]" 
                               value="C" id="q<?php echo $quiz_row['id']; ?>_c">
                        <label class="option-label" for="q<?php echo $quiz_row['id']; ?>_c">
                            <?php echo $quiz_row['option_c']; ?>
                        </label>

                        <input type="radio" name="question[<?php echo $quiz_row['id']; ?>]" 
                               value="D" id="q<?php echo $quiz_row['id']; ?>_d">
                        <label class="option-label" for="q<?php echo $quiz_row['id']; ?>_d">
                            <?php echo $quiz_row['option_d']; ?>
                        </label>
                    </div>
                </div>
                <?php
                $question_number++;
            }
            ?>

            <button type="submit" name="submit_quiz" class="submit-btn">
                <i class="fas fa-check-circle"></i> Submit Quiz
            </button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <h3><i class="fas fa-book-reader"></i> Smart Library Management System</h3>
                <p>An Advanced Digital Library Management System</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>Copyright © <?php echo date("Y"); ?> All Rights Reserved. Foreign Key Friends</p>
            </div>
        </div>
    </footer>
</body>
</html>
