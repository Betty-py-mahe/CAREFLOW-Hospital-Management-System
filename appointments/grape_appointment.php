
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

$message="";


// BOOK APPOINTMENT

if(isset($_POST["save_appointment"]))
{

$patient_id=$_POST["patient_id"];

$doctor_id=$_POST["doctor_id"];

$date=$_POST["appointment_date"];

$time=$_POST["appointment_time"];



$stmt = mysqli_prepare(
    $connection,
    "INSERT INTO appointment
    (patient_id, doctor_id, appointment_date, appointment_time)
    VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "iiss",
    $patient_id,
    $doctor_id,
    $date,
    $time
);

if(mysqli_stmt_execute($stmt))
{

$message="Appointment Booked Successfully";

}

else

{

$message="Appointment Booking Failed";

}


}



include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");


?>


<h2 class="page_title">

📅 Appointment Management

</h2>



<div class="form_box">


<h2>

Book Appointment

</h2>



<form method="POST">


<label>

Select Patient

</label>



<select name="patient_id" required>


<option value="">

Select Patient

</option>



<?php


$patients=mysqli_query(

$connection,

"SELECT * FROM patient"

);



while($patient=mysqli_fetch_assoc($patients))

{


?>


<option value="<?php echo $patient['patient_id']; ?>">


<?php echo $patient['patient_name']; ?>


</option>



<?php

}

?>


</select>





<label>

Select Doctor

</label>



<select name="doctor_id" required>


<option value="">

Select Doctor

</option>



<?php


$doctors=mysqli_query(

$connection,

"SELECT * FROM doctor"

);



while($doctor=mysqli_fetch_assoc($doctors))

{


?>


<option value="<?php echo $doctor['doctor_id']; ?>">


<?php echo $doctor['doctor_name']; ?>


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

required>



<label>

Appointment Time

</label>


<input

type="time"

name="appointment_time"

required>



<br><br>


<input

type="submit"

name="save_appointment"

value="Book Appointment">


</form>



<h3 style="text-align:center;">

<?php echo $message; ?>

</h3>


</div>



<hr>



<h2 style="text-align:center;">

Appointment List

</h2>



<!-- SEARCH SECTION -->

<form method="GET" style="text-align:center;">


<input

type="text"

name="search"

placeholder="Search Patient or Doctor"

class="search_box">



<input

type="date"

name="date_search">



<input

type="submit"

value="Search">


</form>



<br>



<!-- TODAY BUTTON -->

<div style="text-align:center;">

<a href="grape_appointment.php?today=yes">

Today's Appointments

</a>

</div>



<br>



<table border="1" width="90%" align="center">


<tr>


<th>ID</th>

<th>Patient</th>

<th>Doctor</th>

<th>Date</th>

<th>Time</th>

<th>Edit</th>

<th>Delete</th>


</tr>



<?php


// TODAY APPOINTMENTS

if(isset($_GET["today"]))
{


$sql="SELECT


appointment.appointment_id,

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time



FROM appointment



INNER JOIN patient

ON appointment.patient_id = patient.patient_id



INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id



WHERE appointment.appointment_date = CURDATE()



ORDER BY appointment.appointment_time";


}


// SEARCH APPOINTMENTS

elseif(isset($_GET["search"]))
{

    $search = $_GET["search"];

    $date_search = $_GET["date_search"] ?? "";


    $search_value = "%" . $search . "%";

    $date_value = "%" . $date_search . "%";


    $stmt = mysqli_prepare(
        $connection,
        "SELECT
            appointment.appointment_id,
            patient.patient_name,
            doctor.doctor_name,
            appointment.appointment_date,
            appointment.appointment_time

        FROM appointment

        INNER JOIN patient
        ON appointment.patient_id = patient.patient_id

        INNER JOIN doctor
        ON appointment.doctor_id = doctor.doctor_id

        WHERE
            patient.patient_name LIKE ?
            OR doctor.doctor_name LIKE ?
            OR appointment.appointment_date LIKE ?

        ORDER BY appointment.appointment_date DESC"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $search_value,
        $search_value,
        $date_value
    );


    mysqli_stmt_execute($stmt);


    $result = mysqli_stmt_get_result($stmt);

}
// NORMAL VIEW

else
{


$sql="SELECT


appointment.appointment_id,

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time



FROM appointment



INNER JOIN patient

ON appointment.patient_id = patient.patient_id



INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id



ORDER BY appointment.appointment_id DESC";


}



if(isset($_GET["today"]))
{
    $result = mysqli_query($connection,$sql);
}

elseif(isset($_GET["search"]))
{
    /* $result already created by prepared statement */
}

else
{
    $result = mysqli_query($connection,$sql);
}



while($row=mysqli_fetch_assoc($result))

{


?>
<tr>


<td>

<?php echo $row["appointment_id"]; ?>

</td>



<td>

<?php echo $row["patient_name"]; ?>

</td>



<td>

<?php echo $row["doctor_name"]; ?>

</td>



<td>

<?php echo $row["appointment_date"]; ?>

</td>



<td>

<?php echo $row["appointment_time"]; ?>

</td>




<td>


<a href="melon_edit_appointment.php?id=<?php echo $row['appointment_id']; ?>"

class="edit_button">


Edit


</a>


</td>




<td>


<a href="fig_delete_appointment.php?id=<?php echo $row['appointment_id']; ?>"

class="delete_button"

onclick="return confirm('Delete this appointment?')">


Delete


</a>


</td>



</tr>



<?php

}

?>


</table>



<?php

include("../includes/coconut_footer.php");

?>