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


/* Get medical record */

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM medical_record WHERE record_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$record = mysqli_fetch_assoc($result);


/* Check record exists */

if(!$record)
{
    header("Location:kiwi_medical_record.php");
    exit();
}


/* Update medical record */

if(isset($_POST["update_record"]))
{
    $diagnosis = $_POST["diagnosis"];

    $treatment = $_POST["treatment"];

    $medicine = $_POST["medicine"];


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE medical_record
         SET diagnosis=?,
             treatment=?,
             medicine=?
         WHERE record_id=?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $diagnosis,
        $treatment,
        $medicine,
        $id
    );


    if(mysqli_stmt_execute($stmt))
    {
        header("Location:kiwi_medical_record.php");
        exit();
    }
}

?>
<!DOCTYPE html>


<html>

<head>

<meta charset="UTF-8">

<title>Edit Medical Record | CAREFLOW</title>

<link rel="stylesheet" href="../css/watermelon_style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<div class="top_header">

<div class="logo_section">

<img src="../images/logo.png" class="logo">

<div>

<h1>CAREFLOW</h1>

<p>Hospital Management System</p>

</div>

</div>


<div class="header_right">

<div class="system_status">

<i class="fa-solid fa-circle"></i>

System Online

</div>

<div class="admin_name">

<i class="fa-solid fa-user-shield"></i>

Administrator

</div>

</div>

</div>



<div class="form_box">


<h2>

<i class="fa-solid fa-file-medical"></i>

Edit Medical Record

</h2>


<form method="POST">


<label>

Diagnosis

</label>


<textarea

name="diagnosis"

required><?php echo $record["diagnosis"]; ?></textarea>



<label>

Treatment

</label>


<textarea

name="treatment"

required><?php echo $record["treatment"]; ?></textarea>



<label>

Medicine

</label>


<textarea

name="medicine"

required><?php echo $record["medicine"]; ?></textarea>



<input

type="submit"

name="update_record"

value="Update Record">


</form>


</div>


</body>

</html>