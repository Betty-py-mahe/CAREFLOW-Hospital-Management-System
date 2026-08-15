<?php

session_start();

session_unset();

session_destroy();

header("Location:avocado_login.php");
exit();

?>