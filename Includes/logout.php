<?php

session_start();

session_destroy();

echo "<script>window.open('../auth/UserLogin.php','_self')</script>";
