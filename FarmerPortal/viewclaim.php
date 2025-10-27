<?php
session_start();
include("../Includes/db.php");

// Check if the farmer is logged in
if (!isset($_SESSION['phonenumber'])) {
    echo "You must be logged in as a farmer to view claimed books.";
    exit();
}

$farmer_phone = $_SESSION['phonenumber']; // Farmer's phone number from session

// Fetch claimed books for this farmer
$query = "
    SELECT 
        bc.id AS claim_id, 
        bc.buyer_phone, 
        bd.book_title, 
        bd.book_description, 
        bd.condition, 
        br.buyer_name, 
        br.buyer_addr, 
        bc.claim_date 
    FROM 
        book_claims bc
    JOIN 
        book_donations bd ON bc.book_id = bd.id
    JOIN 
        buyerregistration br ON bc.buyer_phone = br.buyer_phone
    WHERE 
        bd.farmer_phone = '$farmer_phone'
    ORDER BY 
        bc.claim_date DESC
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Claimed Books</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
            background-color: white;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Books Claimed by Buyers</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Description</th>
                    <th>Condition</th>
                    <th>Buyer Name</th>
                    <th>Buyer Phone</th>
                    <th>Buyer Address</th>
                    <th>Claim Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['condition']); ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['buyer_addr']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($row['claim_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No claims have been made for your books yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
