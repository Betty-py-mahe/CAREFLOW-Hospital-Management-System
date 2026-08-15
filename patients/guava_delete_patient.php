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
    header("Location:banana_patient.php");
    exit();
}


/* Delete patient securely */

$stmt = mysqli_prepare(
    $connection,
    "DELETE FROM patient WHERE patient_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);


header("Location:banana_patient.php");
exit();

?>