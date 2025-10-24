<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in with a phone number
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in with a phone number as a farmer to post a meet and greet.";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Using phone number from the session

include("../Includes/db.php"); // Include the database connection

// Retrieve farmer_id using phonenumber
$query = "SELECT farmer_id FROM farmerregistration WHERE farmer_phone = '$phonenumber'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $farmer = mysqli_fetch_assoc($result);
    $farmer_id = $farmer['farmer_id'];
} else {
    echo "No farmer found with this phone number.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form inputs
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $meet_link = mysqli_real_escape_string($con, $_POST['meet_link']);
    $meet_date = $_POST['meet_date'];
    $meet_time = $_POST['meet_time'];

    // Insert the meet and greet details into the database with farmer_id as the identifier
    $query = "INSERT INTO meet_and_greet (farmer_id, title, description, meet_link, meet_date, meet_time) 
              VALUES ('$farmer_id', '$title', '$description', '$meet_link', '$meet_date', '$meet_time')";
    
    if (mysqli_query($con, $query)) {
        echo "<div class='alert alert-success text-center'>Meet and greet session posted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error posting meet and greet: " . mysqli_error($con) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Meet and Greet</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">Post a New Meet and Greet</h3>
                </div>
                <div class="card-body">
                    <form action="online_class.php" method="POST">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Enter event title">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Enter event description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="meet_link">Meet Link</label>
                            <input type="url" class="form-control" id="meet_link" name="meet_link" required placeholder="Enter meeting link">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="meet_date">Date</label>
                                <input type="date" class="form-control" id="meet_date" name="meet_date" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="meet_time">Time</label>
                                <input type="time" class="form-control" id="meet_time" name="meet_time" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Post Meet and Greet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
