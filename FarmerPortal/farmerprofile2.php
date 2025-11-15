<?php
include("../Includes/db.php");
session_start();
$sessphonenumber = $_SESSION['phonenumber'];
$sql = "select * from farmerregistration where farmer_phone = '$sessphonenumber' ";
$run_query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($run_query)) {
    $name = $row['farmer_name'];
    $phone = $row['farmer_phone'];
    $address = $row['farmer_address'];
    $pan = $row['farmer_pan'];
    $bank = $row['farmer_bank'];
    $state = $row['farmer_state'];
    $district = $row['farmer_district'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Profile</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 30px 15px;
        }

        .profile-card {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .profile-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 30px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group-prepend .text {
            min-width: 150px;
            font-weight: 600;
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            color: goldenrod;
            font-size: 1rem;
            padding: 12px 15px;
            border-right: 1px solid #ffc107;
            display: flex;
            align-items: center;
        }
        
        /* General Input styling for consistency */
        .form-control-plaintext {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 1rem;
            color: #495057;
            text-align: left;
            border-left: none;
            flex-grow: 1;
        }
        
        .form-control-plaintext:focus {
             background-color: #ffffff;
        }

        .input-group .form-control-plaintext {
             border: none !important; /* Remove individual border when inside input-group */
        }


        /* Edit Button Style */
        .btn-edit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .profile-card {
                padding: 20px;
                border-radius: 15px;
            }
            .profile-header h2 {
                font-size: 1.75rem;
            }
            .input-group-prepend .text {
                min-width: 120px;
                font-size: 0.9rem;
            }
            .form-control-plaintext {
                font-size: 0.9rem;
            }
            .btn-edit {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <h2><i class="fas fa-user-circle mr-2"></i>Your Profile</h2>
        </div>
        <div class="form">
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-user mr-2"></i>Full Name</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $name?>">
            </div>
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-phone-alt mr-2"></i>Phone No.</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $phone ?>">
            </div>
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-home mr-2"></i>Address</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $address ?>">
            </div> 
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-globe-americas mr-2"></i>State</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $state ?>">
            </div> 
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-city mr-2"></i>City</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $district ?>">
            </div> 
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-pencil-alt mr-2"></i>Pan No.</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $pan ?>">
            </div> 
            
            <div class="input-group">
                <div class="input-group-prepend ">
                    <span class="input-group-text text"><i class="fas fa-university mr-2"></i>Account No.</span>
                </div>
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $bank ?> ">
            </div> 
        </div>
        
        <button type="button" class="btn btn-edit d-flex mx-auto btn-lg" onclick="navigateToEditPage()">
            <i class="fas fa-edit mr-2"></i>Edit Profile
        </button>

        <script>
        function navigateToEditPage() {
            // Ensure this link is correct for your Edit page
            window.location.href = "EditProfile.php"; 
        }
        </script>
    </div>
</div>
</body>
</html>