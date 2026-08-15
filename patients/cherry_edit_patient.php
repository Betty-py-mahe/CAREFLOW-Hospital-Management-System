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


/* Get patient details securely */

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM patient WHERE patient_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$patient = mysqli_fetch_assoc($result);


/* Check patient exists */

if(!$patient)
{
    header("Location:banana_patient.php");
    exit();
}


/* Update patient */

if(isset($_POST["update_patient"]))
{
    $name = $_POST["patient_name"];

    $gender = $_POST["gender"];

    $age = $_POST["age"];

    $phone = $_POST["phone"];

    $address = $_POST["address"];


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE patient SET
        patient_name = ?,
        gender = ?,
        age = ?,
        phone = ?,
        address = ?
        WHERE patient_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssissi",
        $name,
        $gender,
        $age,
        $phone,
        $address,
        $id
    );


    if(mysqli_stmt_execute($stmt))
    {
        header("Location:banana_patient.php");

        exit();
    }
}

?>
<!DOCTYPE html>


<html>

<head>

<meta charset="UTF-8">

<title>Edit Patient | CAREFLOW</title>

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

<i class="fa-solid fa-user-pen"></i>

Edit Patient Details

</h2>


<form method="POST">


<label>

Patient Name

</label>


<input

type="text"

name="patient_name"

value="<?php echo $patient['patient_name']; ?>"

required>



<label>

Gender

</label>


<select name="gender" required>


<option value="Male"
<?php if($patient['gender']=="Male") echo "selected"; ?>>

Male

</option>


<option value="Female"
<?php if($patient['gender']=="Female") echo "selected"; ?>>

Female

</option>


<option value="Other"
<?php if($patient['gender']=="Other") echo "selected"; ?>>

Other

</option>


</select>



<label>

Age

</label>


<input

type="number"

name="age"

value="<?php echo $patient['age']; ?>"

required>



<label>

Phone

</label>


<input

type="text"

name="phone"

value="<?php echo $patient['phone']; ?>"

required>



<label>

Address

</label>


<textarea

name="address"

required><?php echo $patient['address']; ?></textarea>



<input

type="submit"

name="update_patient"

value="Update Patient">


</form>


</div>


</body>

</html>