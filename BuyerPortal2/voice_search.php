<?php
// voice_search.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Corner - Voice Search</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background for elegance */
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .voice-search-icon {
            font-size: 80px;
            color: #28a745; /* Green color for the microphone icon */
            margin-top: 20px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .voice-search-icon:hover {
            transform: scale(1.1);
        }

        .search-heading {
            font-size: 2.5rem;
            color: #343a40; /* Dark gray for a professional look */
            margin-bottom: 10px;
        }

        .subheading {
            font-size: 1.2rem;
            color: #6c757d; /* Muted text color */
        }

        /* Footer styling */
        .footer {
            position: fixed;
            bottom: 10px;
            text-align: center;
            width: 100%;
            font-size: 1rem;
            color: #6c757d;
        }

        /* Add a soft glow effect to the icon */
        .voice-search-icon:active {
            transform: scale(0.95);
        }

        .container h1 span {
            color: #28a745; /* Emphasize "Book Corner" in green */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .search-heading {
                font-size: 2rem;
            }

            .subheading {
                font-size: 1rem;
            }

            .voice-search-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>

<div class="container text-center">
    <!-- Heading Section -->
    <h1 class="search-heading">Welcome to <span>Book Corner</span></h1>
    <p class="subheading">Please speak your search query to find your favorite books</p>

    <!-- Voice Search Icon -->
    <i class="fas fa-microphone voice-search-icon" onclick="startVoiceSearch()"></i>
</div>

<!-- Footer with additional info -->
<div class="footer">
    <p>Powered by Book Corner &mdash; Your personal library at a command</p>
</div>

<!-- Voice Search JavaScript Code -->
<script>
    function startVoiceSearch() {
        // Check if the browser supports the SpeechRecognition API
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert("Your browser does not support voice recognition. Please use Google Chrome.");
            return;
        }

        // Initialize the SpeechRecognition object
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'en-US'; // Set language
        recognition.interimResults = false; // We only want final results

        // Start the recognition
        recognition.start();

        // Event handler for result
        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            console.log("Voice input: ", transcript);

            // Redirect to the search results page with the query
            window.location.href = 'SearchResult.php?search=' + encodeURIComponent(transcript);
        };

        // Error handling
        recognition.onerror = function(event) {
            console.error("Speech recognition error detected: ", event.error);
            alert("An error occurred during voice recognition. Please try again.");
            // Optionally, redirect back to the home page
            window.location.href = 'bhome.php';
        };
    }

    // Start voice search automatically when the page loads
    window.onload = function() {
        startVoiceSearch();
    };
</script>

</body>
</html>
