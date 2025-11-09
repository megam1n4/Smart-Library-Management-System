<?php
if (isset($_SESSION['phonenumber'])) {
echo "../UserPortal/UserProfile.php";
}
else {
echo "../auth/UserLogin.php";
}

?>
