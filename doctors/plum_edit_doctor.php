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


/* Get doctor details securely */

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM doctor WHERE doctor_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$doctor = mysqli_fetch_assoc($result);


/* Check doctor exists */

if(!$doctor)
{
    header("Location:orange_doctor.php");
    exit();
}


/* Update doctor */

if(isset($_POST["update_doctor"]))
{
    $name = $_POST["doctor_name"];

    $specialization = $_POST["specialization"];

    $phone = $_POST["phone"];

    $email = $_POST["email"];


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE doctor SET
        doctor_name = ?,
        specialization = ?,
        phone = ?,
        email = ?
        WHERE doctor_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $name,
        $specialization,
        $phone,
        $email,
        $id
    );


    if(mysqli_stmt_execute($stmt))
    {
        header("Location:orange_doctor.php");

        exit();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Edit Doctor | CAREFLOW</title>

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

<i class="fa-solid fa-user-doctor"></i>

Edit Doctor Details

</h2>


<form method="POST">


<label>

Doctor Name

</label>


<input

type="text"

name="doctor_name"

value="<?php echo $doctor['doctor_name']; ?>"

required>



<label>

Specialization

</label>


<input

type="text"

name="specialization"

value="<?php echo $doctor['specialization']; ?>"

required>



<label>

Phone

</label>


<input

type="text"

name="phone"

value="<?php echo $doctor['phone']; ?>"

required>



<label>

Email

</label>


<input

type="email"

name="email"

value="<?php echo $doctor['email']; ?>"

required>



<input

type="submit"

name="update_doctor"

value="Update Doctor">


</form>


</div>


</body>

</html>