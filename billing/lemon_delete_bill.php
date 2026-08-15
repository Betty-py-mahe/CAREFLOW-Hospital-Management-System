<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Billing Staff"
]);

include("../database/peach_database.php");

$id = $_GET["id"] ?? '';

if($id == '')
{
    header("Location:mango_billing.php");
    exit();
}


/* Delete bill */

$stmt = mysqli_prepare(
    $connection,
    "DELETE FROM billing WHERE bill_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


if(mysqli_stmt_execute($stmt))
{
    header("Location:mango_billing.php");
    exit();
}

?>