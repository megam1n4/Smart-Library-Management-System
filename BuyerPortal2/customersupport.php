<?php
session_start(); // Start session if not already started

// Check if the user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to view available meet-and-greet sessions.";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Get the phone number of the logged-in reader

include("../Includes/db.php"); // Include the database connection

// Fetch reader information (e.g., name) based on phone number
$query = "SELECT buyer_name FROM buyerregistration WHERE buyer_phone = '$phonenumber'";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) > 0) {
    $reader = mysqli_fetch_assoc($result);
    $reader_name = $reader['buyer_name'];
} else {
    echo "No reader found with this phone number.";
    exit();
}

// Fetch upcoming meet-and-greet sessions from the database
$meet_and_greet_query = "SELECT mg.title, mg.description, mg.meet_link, mg.meet_date, mg.meet_time, f.farmer_name 
                         FROM meet_and_greet mg
                         JOIN farmerregistration f ON mg.farmer_id = f.farmer_id
                         WHERE mg.meet_date >= CURDATE()
                         ORDER BY mg.meet_date, mg.meet_time";
$meet_and_greet_result = mysqli_query($con, $meet_and_greet_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Meet and Greet Sessions</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Available Meet and Greet Sessions</h2>

    <!-- Greet the logged-in reader -->
    <div class="alert alert-info text-center">
        Welcome, <?php echo htmlspecialchars($reader_name); ?>! Here are the upcoming meet-and-greet sessions.
    </div>

    <?php if (mysqli_num_rows($meet_and_greet_result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($meet_and_greet_result)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><?php echo htmlspecialchars($row['title']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="font-weight-bold">Hosted by: <?php echo htmlspecialchars($row['farmer_name']); ?></p>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($row['meet_date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date("g:i A", strtotime($row['meet_time'])); ?></p>
                            <a href="<?php echo htmlspecialchars($row['meet_link']); ?>" target="_blank" class="btn btn-primary">Join Meet</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No upcoming meet-and-greet sessions are available at the moment. Please check back later!
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
