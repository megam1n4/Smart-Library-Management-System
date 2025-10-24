<?php
session_start();
include("../Includes/db.php");

// Check if debate_id is provided
if (isset($_GET['debate_id'])) {
    $debate_id = (int) $_GET['debate_id'];

    // Fetch debate details
    $query = "
        SELECT 
            dt.id AS debate_id, 
            dt.topic_title, 
            dt.description, 
            dt.link, 
            dt.side_a_name, 
            dt.side_b_name, 
            dt.debate_date, 
            dt.debate_time 
        FROM 
            debate_topics dt
        WHERE 
            dt.id = $debate_id";
    $debate_result = mysqli_query($con, $query);
    $debate = mysqli_fetch_assoc($debate_result);
} else {
    echo "Invalid debate selection.";
    exit();
}

// Fetch participants for each side
$side_a_query = "
    SELECT br.buyer_name 
    FROM debate_registrations dr
    JOIN buyerregistration br ON dr.buyer_phone = br.buyer_phone
    WHERE dr.debate_id = $debate_id AND dr.side = 'A'";
$side_a_result = mysqli_query($con, $side_a_query);

$side_b_query = "
    SELECT br.buyer_name 
    FROM debate_registrations dr
    JOIN buyerregistration br ON dr.buyer_phone = br.buyer_phone
    WHERE dr.debate_id = $debate_id AND dr.side = 'B'";
$side_b_result = mysqli_query($con, $side_b_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Debate Participants</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
        }
        .debate-container {
            max-width: 800px;
            margin: auto;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .list-group-item {
            background-color: #f9f9f9;
            border: none;
        }
        .side-title {
            font-weight: bold;
            color: #007bff;
            font-size: 1.1rem;
        }
        .spectator-link {
            color: #28a745;
            font-weight: bold;
        }
        .no-participants {
            color: #888;
            font-style: italic;
        }
        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container mt-5 debate-container">
    <div class="card mb-4">
        <div class="card-header">
            Debate Topic: <?php echo htmlspecialchars($debate['topic_title']); ?>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($debate['description']); ?></p>
            <p><strong>Date:</strong> <?php echo $debate['debate_date']; ?> at <?php echo $debate['debate_time']; ?></p>
            <p><strong>Link:</strong> <a href="<?php echo htmlspecialchars($debate['link']); ?>" target="_blank" class="spectator-link">Join as Spectator</a></p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="side-title"><?php echo htmlspecialchars($debate['side_a_name']); ?></div>
                    <ul class="list-group mt-2">
                        <?php if (mysqli_num_rows($side_a_result) > 0): ?>
                            <?php while ($participant = mysqli_fetch_assoc($side_a_result)): ?>
                                <li class="list-group-item"><?php echo htmlspecialchars($participant['buyer_name']); ?></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item no-participants">No participants registered for this side yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="col-md-6">
                    <div class="side-title"><?php echo htmlspecialchars($debate['side_b_name']); ?></div>
                    <ul class="list-group mt-2">
                        <?php if (mysqli_num_rows($side_b_result) > 0): ?>
                            <?php while ($participant = mysqli_fetch_assoc($side_b_result)): ?>
                                <li class="list-group-item"><?php echo htmlspecialchars($participant['buyer_name']); ?></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item no-participants">No participants registered for this side yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
