<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Corner</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Full Page Background */
        body {
            background: url('test3.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            overflow: hidden;
        }

        /* Main Container */
        .container {
            text-align: center;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.5s ease-in-out; /* Animation */
        }

        /* Title */
        .container h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: bold;
            animation: slideInDown 1s ease-out; /* Animation */
        }

        /* Button Styling */
        .genre-button {
            font-size: 20px;
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 25px;
            background-color: #3498db;
            color: #ffffff;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s; /* Smooth Transition */
            animation: bounceIn 1.2s ease-out;
        }

        /* Hover Effects */
        .genre-button:hover {
            background-color: #2980b9;
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes bounceIn {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Book Corner</h1>
    <p>Choose your favorite genre for Quiz</p>
    <!-- Genre Buttons -->
    <button class="genre-button" onclick="window.location.href='quiz.php'">Literature</button>
    <button class="genre-button" onclick="window.location.href='quiz.php'">Programming</button>
    <button class="genre-button" onclick="window.location.href='quiz.php'">Music</button>
</div>

</body>
</html>
