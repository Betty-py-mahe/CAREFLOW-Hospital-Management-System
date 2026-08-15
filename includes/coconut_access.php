<?php

if(!isset($_SESSION["username"]))
{
    header("Location:../avocado_login.php");
    exit();
}


/* Check role */

if(!isset($_SESSION["role"]))
{
    session_unset();
    session_destroy();

    header("Location:../avocado_login.php");
    exit();
}


/* Role authorization */

function requireRole($allowed_roles)
{

    if(!in_array($_SESSION["role"], $allowed_roles))
    {
        header("Location:../dashboard/mango_dashboard.php");
        exit();
    }

}

?>