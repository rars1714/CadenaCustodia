<?php
// cadenacustodia/Login/logout.php
session_start();
session_destroy();
header("Location: login.php");
exit();
