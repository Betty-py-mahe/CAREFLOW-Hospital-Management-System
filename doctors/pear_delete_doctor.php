<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor"
]);

include("../database/peach_database.php");

$id = $_GET["id"] ?? '';

if($id == '')
{
    header("Location:orange_doctor.php");
    exit();
}


/* Delete doctor securely */

$stmt = mysqli_prepare(
    $connection,
    "DELETE FROM doctor WHERE doctor_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);


header("Location:orange_doctor.php");
exit();

?>