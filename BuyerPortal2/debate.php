<?php
session_start();
include("../Includes/db.php");

if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to view and register for debates.";
    exit();
}

$phonenumber = $_SESSION['phonenumber'];

$query = "SELECT * FROM debate_topics WHERE debate_date >= CURDATE() ORDER BY debate_date ASC LIMIT 3";
$topics_result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Debate Topics</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Weekly Debate Topics</h2>

    <?php while ($topic = mysqli_fetch_assoc($topics_result)): ?>
        <?php
        $side_a_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM debate_registrations WHERE debate_id = {$topic['id']} AND side = 'A'"))['count'];
        $side_b_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM debate_registrations WHERE debate_id = {$topic['id']} AND side = 'B'"))['count'];
        ?>

        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php echo htmlspecialchars($topic['topic_title']); ?></h4>
                <p class="card-text"><?php echo htmlspecialchars($topic['description']); ?></p>
                <p class="card-text"><strong>Date:</strong> <?php echo $topic['debate_date']; ?> at <?php echo $topic['debate_time']; ?></p>
                <p class="card-text"><strong>Link:</strong> <a href="<?php echo htmlspecialchars($topic['link']); ?>" target="_blank">Join Debate</a></p>

                <form action="register_debate.php" method="POST" class="d-inline">
                    <input type="hidden" name="debate_id" value="<?php echo $topic['id']; ?>">

                    <div class="form-group">
                        <label>Choose a Side:</label>
                        <select name="side" class="form-control" required>
                            <option value="">Select Side</option>
                            <?php if ($side_a_count < 2): ?>
                                <option value="A"><?php echo htmlspecialchars($topic['side_a_name']); ?> (<?php echo 2 - $side_a_count; ?> spots left)</option>
                            <?php endif; ?>
                            <?php if ($side_b_count < 2): ?>
                                <option value="B"><?php echo htmlspecialchars($topic['side_b_name']); ?> (<?php echo 2 - $side_b_count; ?> spots left)</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" <?php if ($side_a_count >= 2 && $side_b_count >= 2) echo 'disabled'; ?>>Register</button>
                </form>

                <!-- View Participants Button -->
                <a href="spectator_view.php?debate_id=<?php echo $topic['id']; ?>" class="btn btn-secondary ml-2">View Participants</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
