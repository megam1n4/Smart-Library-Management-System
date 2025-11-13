<?php
session_start();

// Process login BEFORE any HTML output
include("../Includes/db.php");

if (isset($_POST['login'])) {

    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Simple query - just match phone and password directly
    $query = "SELECT * FROM buyerregistration WHERE buyer_phone = '$phonenumber' AND buyer_password = '$password'";
    $run_query = mysqli_query($con, $query);
    
    if (mysqli_num_rows($run_query) > 0) {
        // Login successful
        $row = mysqli_fetch_array($run_query);
        $_SESSION['phonenumber'] = $phonenumber;
        $_SESSION['buyer_id'] = $row['buyer_id'];
        
        // Redirect to buyer home page
        header("Location: ../BuyerPortal2/bhome.php");
        exit();
    } else {
        // Login failed
        header("Location: UserLogin.php?error=invalid");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buyer Login</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #292b2c 0%, #1a1b1c 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(218, 165, 32, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .login-header h1 {
            color: goldenrod;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 40px 30px;
        }

        .error-message {
            background: #fee;
            border: 2px solid #fcc;
            color: #c33;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #667eea;
            font-size: 18px;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #292b2c 0%, #1a1b1c 100%);
            color: goldenrod;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(41, 43, 44, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: block;
            margin: 10px 0;
            font-size: 14px;
        }

        .login-footer a:hover {
            color: #764ba2;
            transform: translateX(5px);
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                border-radius: 15px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 26px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .form-control {
                padding: 12px 12px 12px 42px;
                font-size: 14px;
            }

            .btn-login {
                padding: 12px;
                font-size: 15px;
            }
        }

        @media (max-width: 360px) {
            body {
                padding: 15px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 25px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Login</h1>
            <p>Welcome back! Please login to your account</p>
        </div>
        
        <div class="login-body">
            <form name="my-form" action="UserLogin.php" method="post">
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                    echo '<div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Invalid phone number or password. Please try again.</span>
                          </div>';
                }
                ?>
                
                <div class="form-group">
                    <label for="phone_number">
                        <i class="fas fa-phone-alt"></i> Phone Number
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone-alt input-icon"></i>
                        <input 
                            type="text" 
                            id="phone_number" 
                            class="form-control" 
                            name="phonenumber" 
                            placeholder="Enter your phone number" 
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="p1">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="p1" 
                            class="form-control" 
                            type="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn-login" name="login" value="Login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="login-footer">
                    <a href="BuyerForgotPassword.php">
                        <i class="fas fa-key"></i> Forgot your password?
                    </a>
                    <a href="BuyerRegistration.php">
                        <i class="fas fa-user-plus"></i> Create New Account
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>