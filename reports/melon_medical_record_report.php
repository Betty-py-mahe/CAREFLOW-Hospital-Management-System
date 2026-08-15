
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
   Administrator / Doctor / Nurse / Reception
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
            medical_record.record_id,
            medical_record.patient_id,
            patient.patient_name,
            medical_record.diagnosis,
            medical_record.treatment,
            medical_record.medicine

         FROM medical_record

         INNER JOIN patient
         ON medical_record.patient_id = patient.patient_id

         ORDER BY medical_record.record_id DESC"
    );


    if (!$csv_result)
    {
        die("Unable to generate medical record report.");
    }


    header("Content-Type: text/csv; charset=utf-8");

    header(
        "Content-Disposition: attachment; filename=medical_record_list.csv"
    );


    $output = fopen("php://output", "w");


    /* CSV HEADER */

    fputcsv(
        $output,
        array(
            "Record ID",
            "Patient ID",
            "Patient Name",
            "Diagnosis",
            "Treatment",
            "Medicine"
        )
    );


    /* CSV DATA */

    while ($row = mysqli_fetch_assoc($csv_result))
    {

        fputcsv(
            $output,
            array(
                $row["record_id"],
                $row["patient_id"],
                $row["patient_name"],
                $row["diagnosis"],
                $row["treatment"],
                $row["medicine"]
            )
        );

    }


    fclose($output);

    exit();

}


/* =========================================
   GET MEDICAL RECORD LIST
   ========================================= */

$record_result = mysqli_query(
    $connection,
    "SELECT
        medical_record.record_id,
        medical_record.patient_id,
        patient.patient_name,
        medical_record.diagnosis,
        medical_record.treatment,
        medical_record.medicine

     FROM medical_record

     INNER JOIN patient
     ON medical_record.patient_id = patient.patient_id

     ORDER BY medical_record.record_id DESC"
);


if (!$record_result)
{
    die(
        "Unable to retrieve medical records: "
        . mysqli_error($connection)
    );
}


$total_records = mysqli_num_rows(
    $record_result
);

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Medical Records | CAREFLOW</title>


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

</style>

</head>


<body>


<?php

/*
   IMPORTANT:
   Header included.
   Sidebar intentionally NOT included.
*/

include("../includes/coconut_header.php");

?>


<div class="report_page">


<div class="report_header">


<div>

<h2 class="report_title">

<i class="fa-solid fa-notes-medical"></i>

Medical Records

</h2>


<p>

List of medical records registered in CAREFLOW.

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
href="melon_medical_record_report.php?download=csv"
class="report_button"
>

<i class="fa-solid fa-file-csv"></i>

Download CSV

</a>


</div>


</div>



<div class="report_summary">

<strong>

Total Medical Records:

</strong>

<?php

echo $total_records;

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

<th>Record ID</th>

<th>Patient ID</th>

<th>Patient Name</th>

<th>Diagnosis</th>

<th>Treatment</th>

<th>Medicine</th>

</tr>

</thead>


<tbody>


<?php

if ($total_records > 0)
{

    while (
        $record =
        mysqli_fetch_assoc($record_result)
    )
    {

?>


<tr>


<td>

<?php

echo htmlspecialchars(
    $record["record_id"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $record["patient_id"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $record["patient_name"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $record["diagnosis"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $record["treatment"]
);

?>

</td>


<td>

<?php

echo htmlspecialchars(
    $record["medicine"]
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
colspan="6"
class="no_records"
>

No medical records found.

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
