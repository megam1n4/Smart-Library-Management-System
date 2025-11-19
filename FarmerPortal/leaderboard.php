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
    <title>Quiz Results Leaderboard</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
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
        
        /* --- Minimal Header/Logo Bar Styling --- */
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

        /* --- Leaderboard Styling (Adapted for theme) --- */
        .leaderboard-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px auto 50px auto; /* Margin adjustment after removing large header */
            max-width: 900px;
            width: 90%;
        }
        .leaderboard-title {
            font-weight: 800;
            color: #292b2c;
            font-size: 2rem;
            margin-bottom: 30px;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            text-align: center;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        th {
            background-color: #292b2c; /* Dark theme header */
            color: goldenrod; /* Gold text */
            text-align: center;
            font-weight: 700;
            padding: 15px;
        }
        td {
             padding: 15px;
             text-align: center;
             border: 1px solid #e9ecef;
        }
        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .rank-badge {
            font-size: 1.2em;
            color: #667eea;
        }
        .top-performer {
            font-weight: bold;
            color: #28a745;
        }
        .time-cell {
            font-family: monospace;
            color: #1a1a2e;
        }
        .footer-note {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 20px;
        }


        /* --- Footer Styling (From LibrarianHomepage.php) --- */
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
<body class="bg-light">

    <div class="top-logo-bar">
        <a href="LibrarianHomepage.php">
            <img src="logo2.jpg" alt="Smart Library Logo">
        </a>
    </div>

    <div class="container-fluid">
        <div class="leaderboard-container">
            <h2 class="leaderboard-title">
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
                        <td class="rank-badge"><?php echo $rank_badge; ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_phone']); ?></td>
                        <td><?php echo $row['score']; ?></td>
                        <td class="time-cell"><?php echo $time_formatted; ?></td>
                    </tr>
                    <?php $rank++; endwhile; ?>
                </tbody>
            </table>

            <div class="text-center footer-note">
                <p><i class="fas fa-info-circle"></i> Scores are based on total correct answers. Ties are broken by time taken.</p>
            </div>
        </div>
    </div>

    <section id="footer" class="myfooter">
        <div class="container">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>
</html>