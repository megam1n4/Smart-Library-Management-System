<?php

session_start();

session_destroy();

echo "<script>window.open('../LibrarianPortal/LibrarianHomepage.php','_self')</script>";


?>