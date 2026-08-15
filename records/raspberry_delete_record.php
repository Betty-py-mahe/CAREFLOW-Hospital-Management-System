<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor",
    "Nurse"
]);

include("../database/peach_database.php");

$id = $_GET["id"] ?? '';

if($id == '')
{
    header("Location:kiwi_medical_record.php");
    exit();
}


/* Delete medical record */

$stmt = mysqli_prepare(
    $connection,
    "DELETE FROM medical_record WHERE record_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if(mysqli_stmt_execute($stmt))
{
    header("Location:kiwi_medical_record.php");
    exit();
}

?>