
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
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"])
)
{
    header("Location: ../avocado_login.php");
    exit();
}


/* =========================================
   GET USER ROLE
   ========================================= */

$user_role = $_SESSION["role"];


/* =========================================
   ROLE ACCESS
========================================= */

$allowed_roles = array(
    "System Administrator",
    "Doctor",
    "Nurse",
    "Reception"
);


if (!in_array($user_role, $allowed_roles))
{
    die("Access denied.");
}


/* =========================================
   DATABASE CONNECTION
   ========================================= */

include("../database/peach_database.php");


/* =========================================
   CSV DOWNLOAD
   ========================================= */

if (
    isset($_GET["download"]) &&
    $_GET["download"] == "csv"
)
{

    $csv_result = mysqli_query(
        $connection,
        "SELECT
            appointment.appointment_id,
            appointment.patient_id,
            patient.patient_name,
            appointment.doctor_id,
            doctor.doctor_name,
            appointment.appointment_date,
            appointment.appointment_time
         FROM appointment

         INNER JOIN patient
         ON appointment.patient_id = patient.patient_id

         INNER JOIN doctor
         ON appointment.doctor_id = doctor.doctor_id

         ORDER BY appointment.appointment_date DESC,
                  appointment.appointment_time DESC"
    );


    if (!$csv_result)
    {
        die("Unable to generate appointment report.");
    }


    header("Content-Type: text/csv; charset=utf-8");

    header(
        "Content-Disposition: attachment; filename=appointment_list.csv"
    );


    $output = fopen("php://output", "w");


    /* CSV HEADER */

    fputcsv(
        $output,
        array(
            "Appointment ID",
            "Patient ID",
            "Patient Name",
            "Doctor ID",
            "Doctor Name",
            "Appointment Date",
            "Appointment Time"
        )
    );


    /* CSV DATA */

    while ($row = mysqli_fetch_assoc($csv_result))
    {

        fputcsv(
            $output,
            array(
                $row["appointment_id"],
                $row["patient_id"],
                $row["patient_name"],
                $row["doctor_id"],
                $row["doctor_name"],
                $row["appointment_date"],
                $row["appointment_time"]
            )
        );

    }


    fclose($output);

    exit();

}


/* =========================================
   GET APPOINTMENT LIST
   ========================================= */

$appointment_result = mysqli_query(
    $connection,
    "SELECT
        appointment.appointment_id,
        appointment.patient_id,
        patient.patient_name,
        appointment.doctor_id,
        doctor.doctor_name,
        appointment.appointment_date,
        appointment.appointment_time

     FROM appointment

     INNER JOIN patient
     ON appointment.patient_id = patient.patient_id

     INNER JOIN doctor
     ON appointment.doctor_id = doctor.doctor_id

     ORDER BY appointment.appointment_date DESC,
              appointment.appointment_time DESC"
);


if (!$appointment_result)
{
    die(
        "Unable to retrieve appointment records: "
        . mysqli_error($connection)
    );
}


$total_appointments = mysqli_num_rows(
    $appointment_result
);

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Appointment List | CAREFLOW</title>


<link rel="stylesheet"
href="/HospitalProject_V2/css/watermelon_style.css?v=10">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


<style>

.report_page
{
    padding:30px;
}


.report_header
{
    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:25px;
}


.report_title
{
    margin:0;
}


.report_header p
{
    margin-top:8px;
}


.report_actions
{
    display:flex;

    gap:10px;

    align-items:center;
}


.report_button
{
    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:7px;

    padding:9px 15px;

    text-decoration:none;

    border:1px solid currentColor;

    border-radius:6px;

    font-family:Georgia, "Times New Roman", serif;

    font-size:14px;

    font-weight:600;

    cursor:pointer;

    background:white;
}


.report_summary
{
    margin-bottom:20px;

    padding:15px;

    border:1px solid #ddd;

    border-radius:8px;

    background:white;
}


.report_table_container
{
    background:white;

    border:1px solid #ddd;

    border-radius:8px;

    overflow-x:auto;
}


.report_table
{
    width:100%;

    border-collapse:collapse;
}


.report_table th
{
    padding:12px;

    text-align:left;

    border-bottom:1px solid #ddd;

    font-weight:600;
}


.report_table td
{
    padding:11px 12px;

    border-bottom:1px solid #eee;
}


.report_table tr:last-child td
{
    border-bottom:none;
}


.no_records
{
    text-align:center;

    padding:30px;
}
html,
body
{
    min-height:100%;
}

body
{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

.report_page
{
    flex:1;
}
</style>

</head>


<body>


<?php

include("../includes/coconut_header.php");

?>


<div class="report_page">


<div class="report_header">


<div>

<h2 class="report_title">

<i class="fa-solid fa-calendar-days"></i>

Appointment List

</h2>


<p>

View registered hospital appointments.

</p>

</div>


<div class="report_actions">


<!-- BACK TO DASHBOARD -->

<a
href="../dashboard/mango_dashboard.php"
class="report_button"
>

<i class="fa-solid fa-house"></i>

Back to Dashboard

</a>


<!-- DOWNLOAD CSV -->

<a
href="melon_appointment_report.php?download=csv"
class="report_button"
>

<i class="fa-solid fa-file-csv"></i>

Download CSV

</a>


</div>


</div>



<div class="report_summary">

<strong>

Total Appointments:

</strong>

<?php

echo $total_appointments;

?>


&nbsp;&nbsp; | &nbsp;&nbsp;


<strong>

Access:

</strong>

<?php

echo htmlspecialchars($user_role);

?>

</div>



<div class="report_table_container">


<table class="report_table">


<thead>

<tr>

<th>Appointment ID</th>

<th>Patient ID</th>

<th>Patient Name</th>

<th>Doctor ID</th>

<th>Doctor Name</th>

<th>Appointment Date</th>

<th>Appointment Time</th>

</tr>

</thead>


<tbody>


<?php

if ($total_appointments > 0)
{

    while (
        $appointment =
        mysqli_fetch_assoc($appointment_result)
    )
    {

?>


<tr>


<td>

<?php

echo htmlspecialchars(
    $appointment["appointment_id"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["patient_id"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["patient_name"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["doctor_id"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["doctor_name"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["appointment_date"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $appointment["appointment_time"]
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

<td
colspan="7"
class="no_records"
>

No appointment records found.

</td>

</tr>


<?php

}

?>


</tbody>

</table>


</div>


</div>


<?php

include("../includes/coconut_footer.php");

?>


</body>

</html>
