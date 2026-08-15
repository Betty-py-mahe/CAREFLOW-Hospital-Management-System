<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor",
    "Nurse",
    "Reception"
]);

include("../database/peach_database.php");

$id = $_GET["id"] ?? '';

if($id == '')
{
    header("Location:grape_appointment.php");
    exit();
}


/* Delete appointment */

$stmt = mysqli_prepare(
    $connection,
    "DELETE FROM appointment WHERE appointment_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if(mysqli_stmt_execute($stmt))
{
    header("Location:grape_appointment.php");
    exit();
}

?>