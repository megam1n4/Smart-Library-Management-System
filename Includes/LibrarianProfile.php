<?php
if (isset($_SESSION['phonenumber'])) {
echo "../LibrarianPortal/LibrarianProfile.php";
}
else {
echo "../auth/LibrarianLogin.php";
}

?>
