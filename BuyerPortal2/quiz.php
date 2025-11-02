<?php
session_start();
include("../Includes/db.php");

if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to take the quiz.";
    exit();
}

$phonenumber = $_SESSION['phonenumber'];

// Fetch buyer's name based on phone number
$name_query = "SELECT buyer_name FROM buyerregistration WHERE buyer_phone = '$phonenumber'";
$name_result = mysqli_query($con, $name_query);
$buyer_data = mysqli_fetch_assoc($name_result);
$buyer_name = $buyer_data['buyer_name'] ?? 'Unknown User';

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time(); // Record the start time in session
}

// Fetch quiz questions
$query = "SELECT * FROM quiz_questions ORDER BY RAND() LIMIT 5";
$questions_result = mysqli_query($con, $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $start_time = $_SESSION['quiz_start_time'];
    $end_time = time();
    $time_taken = $end_time - $start_time; // Time taken in seconds
    
    // Loop through questions and check answers
    foreach ($_POST['answers'] as $question_id => $selected_option) {
        $question_query = "SELECT correct_option FROM quiz_questions WHERE id = $question_id";
        $question_result = mysqli_query($con, $question_query);
        $question = mysqli_fetch_assoc($question_result);
        
        if ($selected_option === $question['correct_option']) {
            $score++;
        }
    }

    // Insert result into `quiz_results` table
    $insert_query = "INSERT INTO quiz_results (buyer_phone, buyer_name, score, time_taken) VALUES ('$phonenumber', '$buyer_name', '$score', '$time_taken')";
    mysqli_query($con, $insert_query);

    // Clear the start time from session
    unset($_SESSION['quiz_start_time']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Literature Quiz</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Styling for navbar and main content */
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #ffdd57 !important;
        }
        .navbar-brand i {
            color: #ffdd57;
        }
        .quiz-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .score-alert {
            margin-top: -1px;
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #0d47a1;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
        .question-card {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .question-card p {
            font-weight: bold;
            font-size: 1.1rem;
        }
        .form-check label {
            font-size: 0.95rem;
            margin-left: 8px;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar with Book Corner Logo -->
<nav class="navbar navbar-expand-xl navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <i class="fas fa-book-reader mr-2"></i> <!-- FontAwesome book icon -->
        Book Corner
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

<!-- Display score if available -->
<?php if (isset($score)): ?>
    <div class="score-alert">
        You scored <?php echo $score; ?> out of 5. Time taken: <?php echo gmdate("i:s", $time_taken); ?> minutes.
    </div>
<?php endif; ?>

<!-- Quiz Section -->
<div class="container mt-5">
    <div class="quiz-container">
        <h2 class="text-center mb-4">Literature Quiz</h2>

        <?php if (!isset($score)): ?>
            <form action="quiz.php" method="POST">
                <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
                    <div class="card question-card">
                        <div class="card-body">
                            <p><?php echo htmlspecialchars($question['question']); ?></p>
                            <div class="form-check">
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="A" required>
                                <label><?php echo htmlspecialchars($question['option_a']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="B">
                                <label><?php echo htmlspecialchars($question['option_b']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="C">
                                <label><?php echo htmlspecialchars($question['option_c']); ?></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="D">
                                <label><?php echo htmlspecialchars($question['option_d']); ?></label>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary btn-block">Submit Quiz</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
