<?php

include("../Includes/db.php");
session_start();
$sessphonenumber = $_SESSION['phonenumber'];
$sql = "select * from farmerregistration where farmer_phone = $sessphonenumber";
$run_query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($run_query)) {
    $name = $row['farmer_name'];
    $pan = $row['farmer_pan'];
    $phone = $row['farmer_phone'];
    $address = $row['farmer_address'];
    $account = $row['farmer_bank'];
    $state = $row['farmer_state'];
    $district = $row['farmer_district'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Librarian Profile</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>

    <script>
        function state() {
            var a = document.getElementById('states').value;
            if (a === 'ANDAMAN & NICOBAR ISLANDS') {
                var array = ['Andamans', 'Nicobars'];
            } else if (a === 'Texas') {
                var array = ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth', 'El Paso', 'Arlington', 'Corpus Christi', 'Plano', 'Laredo', 'Irving', 'Garland', 'Amarillo', 'Grand Prairie', 'McAllen', 'Mesquite', 'Killeen', 'Frisco', 'Brownsville', 'Pasadena'];
            } else if (a === 'Newyork') {
                var array = ['New York City', 'Buffalo', 'Rochester', 'Yonkers', 'Syracuse', 'Albany', 'New Rochelle', 'Mount Vernon', 'Schenectady', 'Utica', 'White Plains', 'Hempstead', 'Troy', 'Niagara Falls', 'Binghamton', 'Freeport', 'Valley Stream', 'Long Beach', 'Spring Valley', 'Rome'];
            } else if (a === 'Colarado') {
                var array = ['Denver', 'Colorado Springs', 'Aurora', 'Fort Collins', 'Lakewood', 'Thornton', 'Arvada', 'Westminster', 'Pueblo', 'Centennial', 'Boulder', 'Greeley', 'Longmont', 'Loveland', 'Broomfield', 'Grand Junction', 'Castle Rock', 'Commerce City', 'Parker', 'Littleton'];
            } else if (a === 'Florida') {
                var array = ['Miami', 'Tampa', 'Orlando', 'St. Petersburg', 'Jacksonville', 'Hialeah', 'Tallahassee', 'Fort Lauderdale', 'Port St. Lucie', 'Cape Coral', 'Pembroke Pines', 'Hollywood', 'Miramar', 'Gainesville', 'Coral Springs', 'Miami Gardens', 'Clearwater', 'Palm Bay', 'Pompano Beach', 'West Palm Beach'];
            } 

            var string = "";
            for (let i = 0; i < array.length; i++) {
                string = string + "<option>" + array[i] + "</option>";

            }
            // Corrected typo nmae to name in the original JS logic
            string = "<select name = 'district'>" + string + "</select>"
            document.getElementById('district').innerHTML = string;
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }

        .edit-card {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            color: goldenrod;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px; /* Pull header out */
            text-align: center;
        }

        .card-header-custom h1 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 25px;
        }
        
        .input-group-prepend .input-label {
            min-width: 150px;
            font-weight: 600;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 8px 0 0 8px;
            border: 1px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
        }

        .form-control, textarea.form-control, .custom-select {
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 10px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, textarea.form-control:focus, .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .input-group {
            border-radius: 8px;
        }
        
        .input-group > .input-group-prepend > .input-label {
            border-radius: 8px 0 0 8px;
            border: none;
        }
        
        /* Disabled Name/Pan fields */
        .form-control[disabled], textarea[disabled] {
            background-color: #f1f1f1;
            color: #6c757d;
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #292b2c 0%, #1a1a2e 100%);
            color: goldenrod;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(41, 43, 44, 0.4);
        }

        /* Change Password Link Button */
        .btn-change-password {
            background: none;
            color: #667eea;
            padding: 10px;
            border: 2px solid #667eea;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 20px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-change-password:hover {
            background-color: #667eea;
            color: white;
            text-decoration: none;
        }

        /* Home Icon */
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #292b2c;
            font-size: 30px;
            transition: color 0.3s;
        }

        .home-link:hover {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <a href="farmerHomepage.php" class="home-link"> <i class="fa fa-home"></i></a>

    <div class="edit-card">
        <div class="card-header-custom">
            <h1>EDIT PROFILE</h1>
        </div>
        
        <form action="EditProfile.php" method="post">
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-user"></i>Name</span>
                    </div>
                    <textarea class="form-control" disabled><?php echo $name; ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-pencil-alt"></i>Pan No.</span>
                    </div>
                    <textarea class="form-control" disabled><?php echo $pan; ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-phone-alt"></i>Phone</span>
                    </div>
                    <input type="text" name="phonenumber" class="form-control" value="<?php echo $phone; ?>" required />
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-home"></i>Address</span>
                    </div>
                    <textarea type="text" name="address" class="form-control" rows="3" required><?php echo $address; ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-globe-americas"></i>State</span>
                    </div>
                    <select name="statevalue" id="states" onchange="state()" class="custom-select" required>
                        <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
                        <option value="Texas">Texas</option>
                        <option value="Newyork">Newyork</option>
                        <option value="Florida">Florida</option>
                        <option value="Colarado">Colarado</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-city"></i>City</span>
                    </div>
                    <select name="district" id="district" class="custom-select" required>
                         <option value="<?php echo $district; ?>"><?php echo $district; ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-label"><i class="fas fa-university"></i>Account No.</span>
                    </div>
                    <input type="text" name="bank" class="form-control" value="<?php echo $account; ?>" required />
                </div>
            </div>

            <input type="submit" name="confirm" class="btn-submit" value="Confirm Update">

        </form>

        <a href="ChangePassword.php" class="btn btn-change-password">
            <i class="fas fa-key mr-2"></i>Change Password
        </a>

    </div>


</body>

</html>

<?php
if (isset($_POST['confirm'])) {
    $phone = mysqli_real_escape_string( $con, $_POST['phonenumber']);
    $address = mysqli_real_escape_string( $con, $_POST['address']);
    $district = mysqli_real_escape_string( $con, $_POST['district']);
    $state = mysqli_real_escape_string( $con, $_POST['statevalue']);
    $account = mysqli_real_escape_string( $con, $_POST['bank']);

    $query = "update farmerregistration 
              set farmer_phone = '$phone', farmer_address = '$address',
              farmer_bank = '$account', farmer_state = '$state',
              farmer_district = '$district'
              where farmer_id 
              in (select farmer_id from farmerregistration 
              where farmer_phone='$sessphonenumber')";
    $run = mysqli_query($con, $query);
    
    $_SESSION['phonenumber'] = $phone;
    echo "<script>window.open('FarmerProfile2.php','_self')</script>";
}
?>