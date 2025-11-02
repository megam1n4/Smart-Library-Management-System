<?php
session_start();
include("../Includes/db.php");

// Fetch leaderboard data
$leaderboard_query = "
    SELECT buyer_name, buyer_phone, score, time_taken 
    FROM quiz_results 
    ORDER BY score DESC, time_taken ASC 
    LIMIT 10"; // Limit to top 10 for the leaderboard
$leaderboard_result = mysqli_query($con, $leaderboard_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Corner Leaderboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Custom styling for the Book Corner logo */
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #ffdd57 !important;
        }
        .navbar-brand i {
            color: #ffdd57;
        }
        /* Leaderboard styling */
        .leaderboard-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .leaderboard-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
        }
        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .rank-badge {
            font-size: 1.2em;
            color: #ff6f61;
        }
        .top-performer {
            font-weight: bold;
            color: #28a745;
        }
        .time-cell {
            font-family: monospace;
            color: #007bff;
        }
        .footer-note {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 20px;
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

<!-- Leaderboard Section -->
<div class="container">
    <div class="leaderboard-container">
        <h2 class="text-center leaderboard-title">
            <i class="fas fa-trophy"></i> Quiz Leaderboard
        </h2>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Score</th>
                    <th>Time Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                while ($row = mysqli_fetch_assoc($leaderboard_result)):
                    $time_formatted = gmdate("i:s", $row['time_taken']);
                    $rank_badge = ($rank === 1) ? '🥇' : (($rank === 2) ? '🥈' : (($rank === 3) ? '🥉' : $rank));
                    $row_class = ($rank <= 3) ? 'top-performer' : '';
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td class="text-center rank-badge"><?php echo $rank_badge; ?></td>
                    <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['buyer_phone']); ?></td>
                    <td class="text-center"><?php echo $row['score']; ?></td>
                    <td class="time-cell text-center"><?php echo $time_formatted; ?></td>
                </tr>
                <?php $rank++; endwhile; ?>
            </tbody>
        </table>

        <div class="text-center footer-note">
            <p><i class="fas fa-info-circle"></i> Scores are based on total correct answers. Ties are broken by time taken.</p>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
