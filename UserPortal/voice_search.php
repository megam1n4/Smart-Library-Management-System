<?php
// voice_search.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Library Management System - Voice Search</title>
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

        .voice-search-icon.listening {
            color: #dc3545; /* Red when listening */
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .status-text {
            font-size: 1.1rem;
            color: #28a745;
            margin-top: 15px;
            min-height: 30px;
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
    <h1 class="search-heading">Welcome to <span>Smart Library Management System </span></h1>
    <p class="subheading">Click the microphone to start, click again to stop</p>

    <!-- Voice Search Icon -->
    <i id="micIcon" class="fas fa-microphone voice-search-icon" onclick="toggleVoiceSearch()"></i>
    
    <!-- Status Text -->
    <p id="statusText" class="status-text">Click the microphone to begin</p>
</div>

<!-- Footer with additional info -->
<div class="footer">
    <p>Powered by Smart Library Management System &mdash; Your personal library at a command</p>
</div>

<!-- Voice Search JavaScript Code -->
<script>
    let recognitionActive = false;
    let currentRecognition = null;
    let fullTranscript = ''; // Store transcript globally
    let silenceTimer = null; // Timer for auto-stop after silence

    function toggleVoiceSearch() {
        if (recognitionActive) {
            // Stop listening and process what we have
            stopListening();
        } else {
            // Start listening
            startListening();
        }
    }

    function startListening() {
        // Check if the browser supports the SpeechRecognition API
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert("Your browser does not support voice recognition. Please use Google Chrome or Microsoft Edge.");
            return;
        }

        // Reset transcript
        fullTranscript = '';

        // Get elements
        const micIcon = document.getElementById('micIcon');
        const statusText = document.getElementById('statusText');

        // First, explicitly request microphone access to see the permission prompt
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function(stream) {
                console.log("✅ Microphone access granted!");
                console.log("Microphone stream:", stream);
                console.log("Audio tracks:", stream.getAudioTracks());
                if (stream.getAudioTracks().length > 0) {
                    console.log("Using microphone:", stream.getAudioTracks()[0].label);
                }
                
                // Stop the test stream - we just needed to trigger the permission
                stream.getTracks().forEach(track => track.stop());
                
                // Now start actual speech recognition
                startSpeechRecognition(micIcon, statusText);
            })
            .catch(function(err) {
                console.error("❌ Microphone access DENIED or ERROR:", err);
                statusText.textContent = 'Microphone access denied! Please allow microphone in browser settings.';
                statusText.style.color = '#dc3545';
                alert('This app needs microphone access to work. Please click "Allow" when prompted, or check your browser settings.');
            });
    }

    function startSpeechRecognition(micIcon, statusText) {
        // Initialize the SpeechRecognition object
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        currentRecognition = recognition;
        
        // Configure for continuous listening until user stops
        recognition.lang = 'en-US';
        recognition.continuous = true; // Keep listening until manually stopped
        recognition.interimResults = true; // Show what's being heard in real-time
        recognition.maxAlternatives = 1;

        recognitionActive = true;

        // Update UI to show listening state
        micIcon.classList.add('listening');
        statusText.textContent = 'Listening... Click mic again when done speaking';
        statusText.style.color = '#dc3545';

        // Start the recognition
        try {
            recognition.start();
            console.log("Voice recognition started - continuous mode");
        } catch (e) {
            console.error("Error starting recognition:", e);
            resetUI();
            statusText.textContent = 'Error starting microphone. Please try again.';
            statusText.style.color = '#dc3545';
            return;
        }

        // Handle interim and final results
        recognition.onresult = function(event) {
            let interimTranscript = '';
            
            // Clear any existing silence timer since user is speaking
            if (silenceTimer) {
                clearTimeout(silenceTimer);
                silenceTimer = null;
            }
            
            // Build the full transcript from all final results
            fullTranscript = '';
            
            for (let i = 0; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                
                if (event.results[i].isFinal) {
                    fullTranscript += transcript + ' ';
                    console.log("Final result added:", transcript);
                    
                    // Start a soft timeout after final result (3 seconds of silence)
                    silenceTimer = setTimeout(function() {
                        console.log("Auto-stopping after silence");
                        if (recognitionActive && fullTranscript.trim()) {
                            stopListening();
                        }
                    }, 3000); // 3 seconds after last final result
                } else {
                    interimTranscript += transcript;
                }
            }

            // Show what we're hearing in real-time
            const displayText = (fullTranscript + interimTranscript).trim();
            if (displayText) {
                statusText.textContent = 'Hearing: "' + displayText + '"';
                statusText.style.color = '#ffc107';
                console.log("Current full transcript: '" + fullTranscript.trim() + "'");
                console.log("Current interim: '" + interimTranscript.trim() + "'");
            }
        };

        // Error handling
        recognition.onerror = function(event) {
            console.error("Speech recognition error:", event.error);
            
            // Only handle critical errors - ignore minor ones during continuous listening
            if (event.error === 'no-speech') {
                // In continuous mode, this is normal - just waiting for speech
                console.log("Waiting for speech...");
                return;
            } else if (event.error === 'audio-capture') {
                recognitionActive = false;
                resetUI();
                statusText.textContent = 'Microphone not found. Please check your microphone.';
                statusText.style.color = '#dc3545';
            } else if (event.error === 'not-allowed') {
                recognitionActive = false;
                resetUI();
                statusText.textContent = 'Microphone blocked. Please allow microphone access.';
                statusText.style.color = '#dc3545';
            } else if (event.error === 'network') {
                recognitionActive = false;
                resetUI();
                statusText.textContent = 'Network error. Check your internet connection.';
                statusText.style.color = '#dc3545';
            } else if (event.error === 'aborted') {
                // This happens when we manually stop - that's expected
                console.log("Recognition aborted (likely manual stop)");
            }
        };

        // When recognition ends unexpectedly
        recognition.onend = function() {
            console.log("Recognition ended");
            
            // Only reset if we didn't manually stop it
            if (recognitionActive) {
                recognitionActive = false;
                resetUI();
                statusText.textContent = 'Recognition stopped. Click to try again.';
                statusText.style.color = '#6c757d';
            }
        };

        // Track when speech is detected
        recognition.onspeechstart = function() {
            console.log("Speech detected");
            statusText.textContent = 'I hear you! Keep speaking or click mic when done...';
            statusText.style.color = '#28a745';
        };

        // Track when audio capture starts
        recognition.onaudiostart = function() {
            console.log("Audio capture started");
        };
    }

    function stopListening() {
        const statusText = document.getElementById('statusText');
        
        // Clear any pending silence timer
        if (silenceTimer) {
            clearTimeout(silenceTimer);
            silenceTimer = null;
        }
        
        if (!currentRecognition) {
            console.log("No active recognition to stop");
            return;
        }

        console.log("Manually stopping recognition");
        console.log("Transcript at stop time:", fullTranscript.trim());
        
        // Stop the recognition
        currentRecognition.stop();
        recognitionActive = false;
        
        // Process after a short delay to ensure all results are captured
        setTimeout(function() {
            processTranscript();
        }, 300);
    }

    function processTranscript() {
        const statusText = document.getElementById('statusText');
        const transcript = fullTranscript.trim();
        
        console.log("Final transcript to search:", transcript);
        
        if (transcript && transcript.length > 0) {
            statusText.textContent = 'Searching for: "' + transcript + '"';
            statusText.style.color = '#28a745';
            
            // Redirect to search results
            setTimeout(function() {
                window.location.href = 'SearchResult.php?search=' + encodeURIComponent(transcript);
            }, 800);
        } else {
            // No speech was captured
            resetUI();
            statusText.textContent = 'No speech detected. Click mic to try again.';
            statusText.style.color = '#ffc107';
        }
    }

    // Helper function to reset UI
    function resetUI() {
        const micIcon = document.getElementById('micIcon');
        micIcon.classList.remove('listening');
        currentRecognition = null;
        recognitionActive = false;
        fullTranscript = '';
        
        // Clear any pending silence timer
        if (silenceTimer) {
            clearTimeout(silenceTimer);
            silenceTimer = null;
        }
    }
</script>

</body>
</html>
