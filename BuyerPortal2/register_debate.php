<?php
session_start();
include("../Includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['phonenumber'])) {
        echo "You must be logged in to register for a debate.";
        exit();
    }

    $phonenumber = $_SESSION['phonenumber'];
    $debate_id = mysqli_real_escape_string($con, $_POST['debate_id']);
    $side = mysqli_real_escape_string($con, $_POST['side']);

    $topic_check = mysqli_query($con, "SELECT * FROM debate_topics WHERE id = '$debate_id'");
    if (mysqli_num_rows($topic_check) === 0) {
        echo "Invalid debate topic selected.";
        exit();
    }

    $already_registered = mysqli_query($con, "SELECT * FROM debate_registrations WHERE debate_id = '$debate_id' AND buyer_phone = '$phonenumber'");
    if (mysqli_num_rows($already_registered) > 0) {
        echo "You are already registered for this debate.";
        exit();
    }

    $side_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM debate_registrations WHERE debate_id = '$debate_id' AND side = '$side'"))['count'];
    if ($side_count >= 2) {
        echo "This side is already full. Please choose a different side.";
        exit();
    }

    $query = "INSERT INTO debate_registrations (debate_id, buyer_phone, side, registered_at) VALUES ('$debate_id', '$phonenumber', '$side', NOW())";
    if (mysqli_query($con, $query)) {
        echo "You have successfully registered for the debate!";
        header("Location: debate.php");
    } else {
        echo "Error in registration: " . mysqli_error($con);
    }
} else {
    echo "Invalid request.";
}
?>
