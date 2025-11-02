<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in to participate in the book discussion.";
    exit();
}

$phonenumber = $_SESSION['phonenumber']; // Get the buyer's phone number

include("../Includes/db.php"); // Include the database connection

// Handle new message and file upload submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = isset($_POST['message']) ? mysqli_real_escape_string($con, $_POST['message']) : '';
    $file_path = null;
    $file_type = null;

    // Check if a file was uploaded
    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $upload_dir = 'uploads/';

        // Create upload directory if it doesn’t exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Define the file path and move the uploaded file
        $file_path = $upload_dir . basename($file_name);
        move_uploaded_file($file_tmp, $file_path);

        // Set file type for images and other files
        $file_type = (strpos(mime_content_type($file_path), 'image') === 0) ? 'image' : 'file';
    }

    // Insert the message or file information into the database
    $query = "INSERT INTO book_discussion_chat (buyer_phone, message, file_path, file_type) 
              VALUES ('$phonenumber', '$message', '$file_path', '$file_type')";
    mysqli_query($con, $query);
}

// Fetch all messages and files along with the buyer's name
$chat_query = "
    SELECT c.message, c.timestamp, c.file_path, c.file_type, IF(b.buyer_phone = '$phonenumber', 'You', b.buyer_name) AS name 
    FROM book_discussion_chat c
    JOIN buyerregistration b ON c.buyer_phone = b.buyer_phone
    ORDER BY c.timestamp ASC";
$chat_result = mysqli_query($con, $chat_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Discussion Chat</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background and Overlay */
        body {
            background: url('test3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: -1;
        }

        /* Chat Container Styling */
        .container {
            max-width: 800px;
            margin-top: 50px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Title Styling */
        h2 {
            color: #333;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Chat Box */
        #chat-box {
            height: 400px;
            overflow-y: auto;
            background-color: #f7f7f7;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Chat Message Styling */
        .chat-message {
            max-width: 75%;
            padding: 10px;
            border-radius: 20px;
            margin-bottom: 15px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .my-message {
            background-color: #d1f5d3;
            color: #333;
            margin-left: auto;
            border-radius: 20px 20px 0 20px;
        }

        .other-message {
            background-color: #ffffff;
            color: #333;
            margin-right: auto;
            border-radius: 20px 20px 20px 0;
        }

        .chat-time {
            font-size: 0.8em;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }

        /* Image Styling */
        .chat-image {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 5px;
            transition: transform 0.2s;
        }

        .chat-image:hover {
            transform: scale(1.05);
        }

        /* File Input and Send Button */
        .message-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-group {
            width: 100%;
        }

        .send-button {
            align-self: flex-end;
            width: 100px;
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s, transform 0.2s;
        }

        .send-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<!-- Background Overlay -->
<div class="overlay"></div>

<div class="container mt-5">
    <h2 class="text-center mb-4">📚 Book Discussion Chat</h2>
    
    <!-- Chat box displaying all messages and files -->
    <div id="chat-box">
        <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
            <div class="chat-message <?php echo ($row['name'] == 'You') ? 'my-message' : 'other-message'; ?>">
                <strong><?php echo htmlspecialchars($row['name']); ?>:</strong>
                
                <!-- Display message text if available -->
                <?php if (!empty($row['message'])): ?>
                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                <?php endif; ?>

                <!-- Display image if file_type is 'image' -->
                <?php if ($row['file_type'] === 'image'): ?>
                    <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="Image" class="chat-image">
                <?php endif; ?>

                <!-- Display download link if file_type is 'file' -->
                <?php if ($row['file_type'] === 'file'): ?>
                    <p><a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>📁 Download File</a></p>
                <?php endif; ?>

                <span class="chat-time"><?php echo date("F j, Y, g:i a", strtotime($row['timestamp'])); ?></span>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Message and file input form -->
    <form action="chat.php" method="POST" enctype="multipart/form-data" class="message-form">
        <div class="form-group">
            <textarea name="message" class="form-control" placeholder="Type your message here..." rows="2"></textarea>
        </div>
        <div class="form-group">
    <label for="file" style="color: black;">Attach a file (optional):</label>
    <input type="file" name="file" class="form-control-file" style="color: black;">
</div>
        <button type="submit" class="btn btn-primary send-button">Send</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Optional JavaScript to Auto-Scroll to the Bottom of Chat -->
<script>
    document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
</script>

</body>
</html>
