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


/* Get appointment details */

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM appointment WHERE appointment_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$appointment = mysqli_fetch_assoc($result);


/* Check appointment exists */

if(!$appointment)
{
    header("Location:grape_appointment.php");
    exit();
}


/* Update appointment */

if(isset($_POST["update"]))
{
    $patient = $_POST["patient_id"];

    $doctor = $_POST["doctor_id"];

    $date = $_POST["appointment_date"];

    $time = $_POST["appointment_time"];


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE appointment
         SET patient_id=?,
             doctor_id=?,
             appointment_date=?,
             appointment_time=?
         WHERE appointment_id=?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "iissi",
        $patient,
        $doctor,
        $date,
        $time,
        $id
    );


    if(mysqli_stmt_execute($stmt))
    {
        header("Location:grape_appointment.php");
        exit();
    }
}

?>
<!DOCTYPE html>

<html>


<head>

<title>

Edit Appointment

</title>


<link rel="stylesheet" href="../css/watermelon_style.css">


</head>


<body>



<div class="header">

<h1>

Edit Appointment

</h1>

</div>



<div class="form_box">


<form method="POST">


<label>

Patient

</label>


<select name="patient_id">


<?php


$patients=mysqli_query(

$connection,

"SELECT * FROM patient"

);



while($p=mysqli_fetch_assoc($patients))

{


?>


<option value="<?php echo $p['patient_id']; ?>"

<?php

if($p['patient_id']==$appointment['patient_id'])

{

echo "selected";

}

?>

>


<?php echo $p['patient_name']; ?>


</option>


<?php

}

?>


</select>




<label>

Doctor

</label>


<select name="doctor_id">


<?php


$doctors=mysqli_query(

$connection,

"SELECT * FROM doctor"

);



while($d=mysqli_fetch_assoc($doctors))

{


?>


<option value="<?php echo $d['doctor_id']; ?>"

<?php

if($d['doctor_id']==$appointment['doctor_id'])

{

echo "selected";

}

?>

>


<?php echo $d['doctor_name']; ?>


</option>


<?php

}

?>


</select>




<label>

Appointment Date

</label>


<input

type="date"

name="appointment_date"

value="<?php echo $appointment['appointment_date']; ?>">



<label>

Appointment Time

</label>


<input

type="time"

name="appointment_time"

value="<?php echo $appointment['appointment_time']; ?>">



<input

type="submit"

name="update"

value="Update Appointment">



</form>


</div>


</body>


</html>