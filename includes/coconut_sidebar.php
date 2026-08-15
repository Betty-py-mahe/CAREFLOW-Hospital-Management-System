<?php

/* =========================================
   START SESSION
   ========================================= */

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}


/* =========================================
   GET USER ROLE
   ========================================= */

$user_role = isset($_SESSION["role"])
    ? $_SESSION["role"]
    : "";

?>

<div class="navigation">


<!-- =========================================
     DASHBOARD
     ========================================= -->

<a href="../dashboard/mango_dashboard.php">

    <i class="fa-solid fa-house"></i>

    Dashboard

</a>


<?php

/* =========================================
   PATIENTS
   Administrator, Doctor, Nurse, Reception
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Doctor" ||
    $user_role == "Nurse" ||
    $user_role == "Reception"
)
{

?>

<a href="../patients/banana_patient.php">

    <i class="fa-solid fa-user"></i>

    Patients

</a>

<?php

}


/* =========================================
   PATIENT LIST REPORT
   Administrator, Doctor, Nurse, Reception
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Doctor" ||
    $user_role == "Nurse" ||
    $user_role == "Reception"
)
{

?>

<a href="../reports/melon_patient_report.php">

    <i class="fa-solid fa-file-medical"></i>

    Patient List

</a>

<?php

}


/* =========================================
   DOCTORS
   Administrator, Doctor
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Doctor"
)
{

?>

<a href="../doctors/orange_doctor.php">

    <i class="fa-solid fa-user-doctor"></i>

    Doctors

</a>


<!-- DOCTOR LIST REPORT -->

<a href="../reports/melon_doctor_report.php">

    <i class="fa-solid fa-file-medical"></i>

    Doctor List

</a>

<?php

}


/* =========================================
   APPOINTMENTS
   Administrator, Doctor, Nurse, Reception
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Doctor" ||
    $user_role == "Nurse" ||
    $user_role == "Reception"
)
{

?>

<a href="../appointments/grape_appointment.php">

    <i class="fa-solid fa-calendar-days"></i>

    Appointments

</a>


<!-- APPOINTMENT LIST REPORT -->

<a href="../reports/melon_appointment_report.php">

    <i class="fa-solid fa-file-medical"></i>

    Appointment List

</a>

<?php

}


/* =========================================
   MEDICAL RECORDS
   Administrator, Doctor, Nurse, Reception
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Doctor" ||
    $user_role == "Nurse" ||
    $user_role == "Reception"
)
{

?>

<a href="../records/kiwi_medical_record.php">

    <i class="fa-solid fa-notes-medical"></i>

    Medical Records

</a>


<!-- MEDICAL RECORD LIST REPORT -->

<a href="../reports/melon_medical_record_report.php">

    <i class="fa-solid fa-file-medical"></i>

    Medical Record List

</a>

<?php

}


/* =========================================
   BILLING
   Administrator, Billing, Reception
   ========================================= */

if (
    $user_role == "System Administrator" ||
    $user_role == "Billing" ||
    $user_role == "Reception"
)
{

?>

<a href="../billing/mango_billing.php">

    <i class="fa-solid fa-file-invoice-dollar"></i>

    Billing

</a>


<!-- BILLING LIST REPORT -->

<a href="../reports/melon_billing_report.php">

    <i class="fa-solid fa-file-invoice"></i>

    Billing List

</a>

<?php

}


/* =========================================
   USER MANAGEMENT
   Administrator only
   ========================================= */

if ($user_role == "System Administrator")
{

?>

<a href="../admin/apple_user_management.php">

    <i class="fa-solid fa-users-gear"></i>

    User Management

</a>

<?php

}


/* =========================================
   LOGOUT
   ========================================= */

?>

<a href="../avocado_logout.php">

    <i class="fa-solid fa-right-from-bracket"></i>

    Logout

</a>


</div>