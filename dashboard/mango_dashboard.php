
<?php

/* =========================================
   START SESSION
   ========================================= */

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}


/* =========================================
   CHECK LOGIN
   ========================================= */

if (
    !isset($_SESSION["username"]) ||
    !isset($_SESSION["role"])
)
{
    header("Location: ../avocado_login.php");
    exit();
}


/* =========================================
   DATABASE
   ========================================= */

include("../database/peach_database.php");


/* =========================================
   HEADER
   ========================================= */

include("../includes/coconut_header.php");


/* =========================================
   SIDEBAR
   ========================================= */

include("../includes/coconut_sidebar.php");


/* =========================================
   DASHBOARD COUNTS
   ========================================= */


/* PATIENT COUNT */

$patient_count = mysqli_query(
    $connection,
    "SELECT COUNT(*) AS total FROM patient"
);

$patient = mysqli_fetch_assoc($patient_count);


/* DOCTOR COUNT */

$doctor_count = mysqli_query(
    $connection,
    "SELECT COUNT(*) AS total FROM doctor"
);

$doctor = mysqli_fetch_assoc($doctor_count);


/* APPOINTMENT COUNT */

$appointment_count = mysqli_query(
    $connection,
    "SELECT COUNT(*) AS total FROM appointment"
);

$appointment = mysqli_fetch_assoc($appointment_count);


/* BILLING COUNT */

$billing_count = mysqli_query(
    $connection,
    "SELECT COUNT(*) AS total FROM billing"
);

$billing = mysqli_fetch_assoc($billing_count);


/* MEDICAL RECORD COUNT */

$record_count = mysqli_query(
    $connection,
    "SELECT COUNT(*) AS total FROM medical_record"
);

$record = mysqli_fetch_assoc($record_count);

?>


<div class="dashboard_page">


<!-- =========================================
     PAGE TITLE
     ========================================= -->

<h2 class="page_title">

Hospital Dashboard

</h2>


<!-- =========================================
     WELCOME BOX
     ========================================= -->

<div class="welcome_box">

<h2>

Welcome
<?php

echo htmlspecialchars(
    $_SESSION["full_name"] ?? $_SESSION["username"]
);

?>



</h2>


<p>

Welcome to the CAREFLOW Hospital Management System.

</p>


<p>

Today's Date :

<?php

echo date("d F Y");

?>

</p>


<p>

Current Time :

<span id="clock"></span>

</p>

</div>


<!-- =========================================
     DASHBOARD CARDS
     ========================================= -->

<div class="dashboard_container">


<!-- PATIENTS -->

<div class="dashboard_card">

<div class="card_icon">

<i class="fa-solid fa-user"></i>

</div>

<div class="card_title">

Patients

</div>

<div class="card_number">

<?php

echo $patient["total"];

?>

</div>

</div>


<!-- DOCTORS -->

<div class="dashboard_card">

<div class="card_icon">

<i class="fa-solid fa-user-doctor"></i>

</div>

<div class="card_title">

Doctors

</div>

<div class="card_number">

<?php

echo $doctor["total"];

?>

</div>

</div>


<!-- APPOINTMENTS -->

<div class="dashboard_card">

<div class="card_icon">

<i class="fa-solid fa-calendar-days"></i>

</div>

<div class="card_title">

Appointments

</div>

<div class="card_number">

<?php

echo $appointment["total"];

?>

</div>

</div>


<!-- BILLING -->

<div class="dashboard_card">

<div class="card_icon">

<i class="fa-solid fa-money-bill-wave"></i>

</div>

<div class="card_title">

Bills

</div>

<div class="card_number">

<?php

echo $billing["total"];

?>

</div>

</div>


<!-- MEDICAL RECORDS -->

<div class="dashboard_card">

<div class="card_icon">

<i class="fa-solid fa-file-medical"></i>

</div>

<div class="card_title">

Medical Records

</div>

<div class="card_number">

<?php

echo $record["total"];

?>

</div>

</div>


</div>


<!-- =========================================
     RECENT APPOINTMENTS
     ========================================= -->

<div class="form_box">

<h2>

Recent Appointments

</h2>


<br>


<table>

<tr>

<th>Patient</th>

<th>Doctor</th>

<th>Date</th>

<th>Time</th>

</tr>


<?php


$query = "

SELECT

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time

FROM appointment

INNER JOIN patient

ON appointment.patient_id = patient.patient_id

INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id

ORDER BY appointment.appointment_id DESC

LIMIT 5

";


$result = mysqli_query(
    $connection,
    $query
);


if (
    $result &&
    mysqli_num_rows($result) > 0
)
{

    while (
        $row =
        mysqli_fetch_assoc($result)
    )
    {

?>


<tr>

<td>

<?php

echo htmlspecialchars(
    $row["patient_name"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $row["doctor_name"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $row["appointment_date"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $row["appointment_time"]
);

?>

</td>

</tr>


<?php

    }

}

else

{

?>


<tr>

<td colspan="4">

No recent appointments found.

</td>

</tr>


<?php

}

?>


</table>

</div>


<!-- =========================================
     QUICK ACTIONS
     ========================================= -->

<div class="form_box">

<h2>

Quick Actions

</h2>


<br>


<div class="quick_action_container">


<!-- ADD PATIENT -->

<a
href="../patients/banana_patient.php"
class="quick_button"
>

<i class="fa-solid fa-user-plus"></i>

<br><br>

Add Patient

</a>


<!-- ADD DOCTOR -->

<a
href="../doctors/orange_doctor.php"
class="quick_button"
>

<i class="fa-solid fa-user-doctor"></i>

<br><br>

Add Doctor

</a>


<!-- APPOINTMENT -->

<a
href="../appointments/grape_appointment.php"
class="quick_button"
>

<i class="fa-solid fa-calendar-plus"></i>

<br><br>

Appointment

</a>


<!-- BILLING -->

<a
href="../billing/mango_billing.php"
class="quick_button"
>

<i class="fa-solid fa-file-invoice-dollar"></i>

<br><br>

Billing

</a>


<!-- MEDICAL RECORDS -->

<a
href="../records/kiwi_medical_record.php"
class="quick_button"
>

<i class="fa-solid fa-file-medical"></i>

<br><br>

Medical Records

</a>


</div>

</div>


</div>


<!-- =========================================
     LIVE CLOCK
     ========================================= -->

<script>

function updateClock()
{

    let now = new Date();

    let clock =
        document.getElementById("clock");

    if (clock)
    {
        clock.innerHTML =
            now.toLocaleTimeString();
    }

}


setInterval(
    updateClock,
    1000
);


updateClock();

</script>


<?php

include("../includes/coconut_footer.php");

?>
