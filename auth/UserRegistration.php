<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buyer Registration - Smart Library</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Registration Header */
        .registration-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .registration-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .registration-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Registration Container */
        .registration-container {
            max-width: 900px;
            margin: 0 auto 50px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            flex: 1;
        }

        .registration-container h2 {
            color: #1a1a2e;
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }

        .form-group label i {
            color: #28a745;
            font-size: 1.1rem;
        }

        .form-control,
        .form-control:focus {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Submit Button */
        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 50px;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 1rem;
        }

        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Footer */
        .myfooter {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffc107;
            padding: 40px 0 20px 0;
            margin-top: auto;
        }

        .social li {
            margin: 0 10px;
        }

        .social a {
            color: #ffc107;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .social a:hover {
            color: #28a745;
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .registration-header h1 {
                font-size: 2rem;
            }

            .registration-header {
                padding: 40px 0;
            }

            .registration-container {
                padding: 25px;
                margin: 0 15px 30px 15px;
            }

            .registration-container h2 {
                font-size: 1.5rem;
            }

            .btn-register {
                padding: 12px 30px;
                font-size: 1.1rem;
            }
        }

        /* Form Row Styling */
        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }

        /* Error/Success Messages */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <!-- Registration Header -->
    <div class="registration-header">
        <h1>📝 Create Your Account</h1>
        <p>Join Smart Library Management System</p>
    </div>

    <!-- Registration Form -->
    <div class="container">
        <div class="registration-container">
            <h2>Registration Form</h2>
            
            <form name="my-form" action="BuyerRegistration.php" method="post">
                <!-- Full Name -->
                <div class="form-group">
                    <label for="full_name">
                        <i class="fas fa-user"></i>
                        Full Name
                    </label>
                    <input type="text" id="full_name" class="form-control" name="name" placeholder="Enter Your Full Name" required>
                </div>

                <!-- Phone Number and Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">
                            <i class="fas fa-phone-alt"></i>
                            Phone Number
                        </label>
                        <input type="text" id="phone_number" class="form-control" name="phonenumber" placeholder="Enter Phone Number" required>
                    </div>

                    <div class="form-group">
                        <label for="email_address">
                            <i class="far fa-envelope"></i>
                            E-Mail Address
                        </label>
                        <input type="email" id="email_address" class="form-control" name="mail" placeholder="Enter Email Address" required>
                    </div>
                </div>

                <!-- Present Address -->
                <div class="form-group">
                    <label for="present_address">
                        <i class="fas fa-home"></i>
                        Present Address
                    </label>
                    <textarea id="present_address" class="form-control" name="address" placeholder="Enter Your Complete Address" required></textarea>
                </div>

                <!-- Company Name and License Number -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">
                            <i class="fas fa-building"></i>
                            Company Name
                        </label>
                        <input type="text" id="company_name" class="form-control" name="company_name" placeholder="Enter Company Name" required>
                    </div>

                    <div class="form-group">
                        <label for="license">
                            <i class="fas fa-id-card"></i>
                            License Number
                        </label>
                        <input type="text" id="license" class="form-control" name="license" placeholder="Enter License Number" required>
                    </div>
                </div>

                <!-- Bank Account and PAN -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="account">
                            <i class="fas fa-university"></i>
                            Bank Account
                        </label>
                        <input type="text" id="account" class="form-control" name="account" placeholder="Enter Bank Account Number" required>
                    </div>

                    <div class="form-group">
                        <label for="pan">
                            <i class="fas fa-credit-card"></i>
                            PAN Number
                        </label>
                        <input type="text" id="pan" class="form-control" name="pan" placeholder="Enter PAN Number" required>
                    </div>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="user_name">
                        <i class="fas fa-user-circle"></i>
                        Username
                    </label>
                    <input type="text" id="user_name" class="form-control" name="username" placeholder="Choose a Username" required>
                </div>

                <!-- Password and Confirm Password -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="p1">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <input id="p1" class="form-control" type="password" name="password" placeholder="Enter Password" required>
                    </div>

                    <div class="form-group">
                        <label for="p2">
                            <i class="fas fa-lock"></i>
                            Confirm Password
                        </label>
                        <input id="p2" class="form-control" type="password" name="confirmpassword" placeholder="Confirm Password" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register" name="register" value="Register">
                    <i class="fas fa-user-plus"></i> Register Now
                </button>

                <!-- Login Link -->
                <div class="login-link">
                    Already have an account? <a href="BuyerLogin.php">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <section id="footer" class="myfooter">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <ul class="list-unstyled list-inline social text-center">
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fab fa-google-plus"></i></a></li>
                        <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2 text-center">
                    <p><strong>Smart Library Management System</strong> - An Advanced Digital Library Management System</p>
                    <p>Copyright © All Rights Reserved. Foreign Key Friends</p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>

</html>

<?php

include("../Includes/db.php");

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $company_name = mysqli_real_escape_string($con, $_POST['company_name']);
    $license = mysqli_real_escape_string($con, $_POST['license']);
    $account = mysqli_real_escape_string($con, $_POST['account']);
    $pan = mysqli_real_escape_string($con, $_POST['pan']);
    $mail = mysqli_real_escape_string($con, $_POST['mail']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);

    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $encryption_iv = '2345678910111211';
    $encryption_key = "DE";

    $encryption = openssl_encrypt(
        $password,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    if (strcmp($password, $confirmpassword) == 0) {

        $query = "insert into buyerregistration (buyer_name,buyer_phone,buyer_addr,buyer_comp,
        buyer_license,buyer_bank,buyer_pan,buyer_mail,buyer_username,buyer_password) 
        values ('$name','$phonenumber','$address','$company_name','$license','$account','$pan',
        '$mail','$username','$encryption')";

        $run_register_query = mysqli_query($con, $query);
        echo "<script>alert('Successfully Registered! Please login to continue.');</script>";
        echo "<script>window.open('UserLogin.php','_self')</script>";
    } else if (strcmp($password, $confirmpassword) != 0) {
        echo "<script>
            alert('Password and Confirm Password should be the same');
        </script>";
    }
}

?>