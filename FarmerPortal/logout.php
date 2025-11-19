<?php

session_start();

session_destroy();

echo "<script>window.open('../FarmerPortal/LibrarianHomepage.php','_self')</script>";


?>