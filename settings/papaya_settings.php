
<?php

session_start();

include("../includes/coconut_access.php");

include("../database/peach_database.php");


$role = $_SESSION["role"];

$message = "";

$password_message = "";

$password_verified = false;


/* =========================================
   GET CURRENT USER DETAILS
   ========================================= */

$user_id = $_SESSION["user_id"];


$stmt = mysqli_prepare(
    $connection,
    "SELECT
        full_name,
        username,
        role,
        employee_id,
        department,
        shift_status,
        covering_proxy,
        email_notifications,
        urgent_patient_alerts,
        appointment_notifications,
        password
     FROM user_login
     WHERE user_id=?"
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$current_user = mysqli_fetch_assoc($result);


/* =========================================
   UPDATE PROFILE
   ========================================= */

if(isset($_POST["save_profile"]))
{

    $full_name = $_POST["full_name"];

    $department = $_POST["department"];

    $shift_status = $_POST["shift_status"];


    $covering_proxy = !empty($_POST["covering_proxy"])
        ? $_POST["covering_proxy"]
        : NULL;


    $email_notifications =
        isset($_POST["email_notifications"]) ? 1 : 0;


    $urgent_patient_alerts =
        isset($_POST["urgent_patient_alerts"]) ? 1 : 0;


    $appointment_notifications =
        isset($_POST["appointment_notifications"]) ? 1 : 0;


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE user_login
         SET full_name=?,
             department=?,
             shift_status=?,
             covering_proxy=?,
             email_notifications=?,
             urgent_patient_alerts=?,
             appointment_notifications=?
         WHERE user_id=?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "sssiiiii",
        $full_name,
        $department,
        $shift_status,
        $covering_proxy,
        $email_notifications,
        $urgent_patient_alerts,
        $appointment_notifications,
        $user_id
    );


    if(mysqli_stmt_execute($stmt))
    {

        $_SESSION["full_name"] = $full_name;

        $message = "Profile updated successfully.";


        $current_user["full_name"] = $full_name;

        $current_user["department"] = $department;

        $current_user["shift_status"] = $shift_status;

        $current_user["covering_proxy"] = $covering_proxy;

        $current_user["email_notifications"] =
            $email_notifications;

        $current_user["urgent_patient_alerts"] =
            $urgent_patient_alerts;

        $current_user["appointment_notifications"] =
            $appointment_notifications;

    }

    else
    {

        $message = "Profile update failed.";

    }

}


/* =========================================
   CHANGE PASSWORD
   ========================================= */

/* Verify current password */

if(isset($_POST["verify_current_password"]))
{

    $current_password =
        $_POST["current_password"];


    if(
        password_verify(
            $current_password,
            $current_user["password"]
        )
    )
    {

        $password_verified = true;

    }

    else
    {

        $password_message =
            "Current password is incorrect.";

    }

}


/* Update password */

if(isset($_POST["save_new_password"]))
{

    $new_password =
        $_POST["new_password"];

    $repeat_password =
        $_POST["repeat_password"];


    if($new_password !== $repeat_password)
    {

        $password_message =
            "New passwords do not match.";

        $password_verified = true;

    }

    else if(strlen($new_password) < 8)
    {

        $password_message =
            "New password must contain at least 8 characters.";

        $password_verified = true;

    }

    else
    {

        $new_hashed_password =
            password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );


        $update_password_stmt =
            mysqli_prepare(
                $connection,
                "UPDATE user_login
                 SET password=?
                 WHERE user_id=?"
            );


        mysqli_stmt_bind_param(
            $update_password_stmt,
            "si",
            $new_hashed_password,
            $user_id
        );


        if(mysqli_stmt_execute($update_password_stmt))
        {

            $password_message =
                "Password changed successfully.";

            $password_verified = false;


            /* Update current password in memory */

            $current_user["password"] =
                $new_hashed_password;

        }

        else
        {

            $password_message =
                "Password update failed.";

            $password_verified = true;

        }

    }

}


/* =========================================
   GET STAFF FOR COVERING PROXY
   ========================================= */

$staff_result = mysqli_query(
    $connection,
    "SELECT
        user_id,
        full_name,
        role,
        department
     FROM user_login
     WHERE status='Active'
     AND user_id != '$user_id'
     ORDER BY full_name"
);


/* =========================================
   GET CARE TEAM STAFF
   ========================================= */

$team_result = mysqli_query(
    $connection,
    "SELECT
        user_id,
        full_name,
        employee_id,
        role,
        department,
        status
     FROM user_login
     ORDER BY department, full_name"
);

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Settings | CAREFLOW

</title>


<link rel="stylesheet"
href="/HospitalProject_V2/css/watermelon_style.css?v=4">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


<style>

/* =========================================
   SETTINGS OPTION ALIGNMENT
   ========================================= */

.option_group
{
    display:flex;

    flex-direction:column;

    gap:12px;

    margin-top:10px;
}


.option_item
{
    display:flex;

    align-items:center;

    gap:10px;

    width:fit-content;

    margin:0;

    padding:0;

    cursor:pointer;
}


.option_item input[type="radio"],
.option_item input[type="checkbox"]
{
    width:16px;

    height:16px;

    margin:0;

    padding:0;

    flex-shrink:0;

    cursor:pointer;
}


.option_item span
{
    display:inline-block;

    line-height:16px;
}


/* =========================================
   CARE PATHWAYS
   ========================================= */

.pathway_container
{
    display:flex;

    flex-direction:column;

    gap:15px;

    margin-top:20px;
}


.pathway_card
{
    display:flex;

    align-items:flex-start;

    gap:15px;

    padding:16px;

    border:1px solid #ddd;

    border-radius:8px;

    background:#fff;
}


.pathway_icon
{
    width:40px;

    min-width:40px;

    text-align:center;

    font-size:22px;
}


.pathway_card h3
{
    margin:0 0 6px 0;
}


.pathway_card p
{
    margin:0;

    line-height:1.5;
}

</style>

</head>


<body>


<?php

include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");

?>


<div class="settings_page">


<h2 class="page_title">

<i class="fa-solid fa-gear"></i>

Settings

</h2>


<div class="settings_container">


<!-- =========================================
     LEFT SETTINGS MENU
     ========================================= -->

<div class="settings_menu">


<!-- MY PROFILE -->

<button

class="settings_tab active"

onclick="showSettings('profile', this)">

<i class="fa-solid fa-user"></i>

My Profile

</button>


<!-- NOTIFICATIONS -->

<button

class="settings_tab"

onclick="showSettings('notifications', this)">

<i class="fa-solid fa-bell"></i>

Notifications

</button>


<?php

if($role == "System Administrator")

{

?>


<!-- CARE TEAMS -->

<button

class="settings_tab"

onclick="showSettings('teams', this)">

<i class="fa-solid fa-users"></i>

Care Teams

</button>


<!-- CARE PATHWAYS -->

<button

class="settings_tab"

onclick="showSettings('pathways', this)">

<i class="fa-solid fa-clipboard-list"></i>

Care Pathways

</button>


<!-- EHR & DEVICES -->

<button

class="settings_tab"

onclick="showSettings('ehr', this)">

<i class="fa-solid fa-plug"></i>

EHR & Devices

</button>


<!-- SECURITY & LOGS -->

<button

class="settings_tab"

onclick="showSettings('security', this)">

<i class="fa-solid fa-shield-halved"></i>

Security & Logs

</button>


<?php

}

?>


</div>


<!-- =========================================
     RIGHT SETTINGS CONTENT
     ========================================= -->

<div class="settings_content">


<!-- =========================================
     MY PROFILE
     ========================================= -->

<div

id="profile"

class="settings_section active">


<h2>

<i class="fa-solid fa-user"></i>

My Profile

</h2>


<p>

Manage your personal information, schedule and shift coverage.

</p>


<hr>


<?php

if($message != "")

{

echo "<h3 style='text-align:center;'>$message</h3>";

}

?>


<form method="POST">


<div class="settings_form_row">


<div>

<label>

Full Name

</label>


<input

type="text"

name="full_name"

value="<?php echo htmlspecialchars($current_user['full_name']); ?>"

required>

</div>


<div>

<label>

Job Role

</label>


<input

type="text"

value="<?php echo htmlspecialchars($current_user['role']); ?>"

readonly>

</div>


</div>


<div class="settings_form_row">


<div>

<label>

Employee ID

</label>


<input

type="text"

value="<?php echo htmlspecialchars($current_user['employee_id']); ?>"

readonly>

</div>


<div>

<label>

Department

</label>


<input

type="text"

name="department"

value="<?php echo htmlspecialchars($current_user['department']); ?>"

required>

</div>


</div>


<label>

Username

</label>


<input

type="text"

value="<?php echo htmlspecialchars($current_user['username']); ?>"

readonly>


<!-- CURRENT SHIFT STATUS -->

<h3>

Current Shift Status

</h3>


<div class="option_group">


<label class="option_item">

<input

type="radio"

name="shift_status"

value="On Duty"

<?php

if($current_user["shift_status"] == "On Duty")

{

echo "checked";

}

?>

>

<span>

On Duty

</span>

</label>


<label class="option_item">

<input

type="radio"

name="shift_status"

value="In Surgery"

<?php

if($current_user["shift_status"] == "In Surgery")

{

echo "checked";

}

?>

>

<span>

In Surgery

</span>

</label>


<label class="option_item">

<input

type="radio"

name="shift_status"

value="Off Duty"

<?php

if($current_user["shift_status"] == "Off Duty")

{

echo "checked";

}

?>

>

<span>

Off Duty

</span>

</label>


</div>


<!-- COVERING PROXY -->

<h3>

Covering Proxy

</h3>


<p>

Select another active staff member who can cover your responsibilities when you are off duty.

</p>


<select name="covering_proxy">


<option value="">

No Covering Proxy

</option>


<?php

while($staff = mysqli_fetch_assoc($staff_result))

{

?>


<option

value="<?php echo $staff["user_id"]; ?>"

<?php

if(
    $current_user["covering_proxy"]
    == $staff["user_id"]
)

{

echo "selected";

}

?>

>


<?php

echo htmlspecialchars($staff["full_name"]);

?>

-

<?php

echo htmlspecialchars($staff["role"]);

?>


</option>


<?php

}

?>


</select>


<!-- NOTIFICATION PREFERENCES -->

<h3>

Notification Preferences

</h3>


<div class="option_group">


<label class="option_item">

<input

type="checkbox"

name="email_notifications"

<?php

if($current_user["email_notifications"] == 1)

{

echo "checked";

}

?>

>

<span>

Email Notifications

</span>

</label>


<label class="option_item">

<input

type="checkbox"

name="urgent_patient_alerts"

<?php

if($current_user["urgent_patient_alerts"] == 1)

{

echo "checked";

}

?>

>

<span>

Urgent Patient Alerts

</span>

</label>


<label class="option_item">

<input

type="checkbox"

name="appointment_notifications"

<?php

if($current_user["appointment_notifications"] == 1)

{

echo "checked";

}

?>

>

<span>

Appointment Notifications

</span>

</label>


</div>


<br>


<input

type="submit"

name="save_profile"

value="Save Changes">


</form>


</div>


<!-- =========================================
     NOTIFICATIONS
     ========================================= -->

<div

id="notifications"

class="settings_section">


<h2>

<i class="fa-solid fa-bell"></i>

Notifications

</h2>


<p>

Manage alerts and notifications for important hospital tasks.

</p>


<hr>


<div class="option_group">


<label class="option_item">

<input

type="checkbox"

checked>

<span>

Email Notifications

</span>

</label>


<label class="option_item">

<input

type="checkbox"

checked>

<span>

Urgent Patient Alerts

</span>

</label>


<label class="option_item">

<input

type="checkbox">

<span>

Appointment Notifications

</span>

</label>


</div>


<br>


<input

type="submit"

value="Save Notification Settings">


</div>


<?php

if($role == "System Administrator")

{

?>


<!-- =========================================
     CARE TEAMS
     ========================================= -->

<div

id="teams"

class="settings_section">


<h2>

<i class="fa-solid fa-users"></i>

Care Teams

</h2>


<p>

View doctors, nurses and staff assigned to hospital departments.

</p>


<hr>


<table class="care_team_table">


<tr>

<th>Staff Name</th>

<th>Employee ID</th>

<th>Role</th>

<th>Department</th>

<th>Status</th>

</tr>


<?php

if(mysqli_num_rows($team_result) > 0)

{

while($team = mysqli_fetch_assoc($team_result))

{

?>


<tr>

<td>

<?php

echo htmlspecialchars($team["full_name"]);

?>

</td>


<td>

<?php

echo htmlspecialchars($team["employee_id"]);

?>

</td>


<td>

<?php

echo htmlspecialchars($team["role"]);

?>

</td>


<td>

<?php

echo htmlspecialchars($team["department"]);

?>

</td>


<td>

<?php

echo htmlspecialchars($team["status"]);

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

<td colspan="5" style="text-align:center;">

No staff members found.

</td>

</tr>


<?php

}

?>


</table>


</div>


<!-- =========================================
     CARE PATHWAYS
     ========================================= -->

<div

id="pathways"

class="settings_section">


<h2>

<i class="fa-solid fa-clipboard-list"></i>

Care Pathways

</h2>


<p>

Standard clinical workflows used to guide patient care across hospital departments.

</p>


<hr>


<div class="pathway_container">


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-truck-medical"></i>

</div>


<div>

<h3>

Emergency / Casualty

</h3>


<p>

Patient triage, emergency assessment, investigation, treatment and disposition.

</p>

</div>

</div>


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-hospital-user"></i>

</div>


<div>

<h3>

Inpatient Care

</h3>


<p>

Admission, clinical assessment, treatment, monitoring and discharge planning.

</p>

</div>

</div>


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-pills"></i>

</div>


<div>

<h3>

Medication Management

</h3>


<p>

Prescription, verification, dispensing, administration and medication monitoring.

</p>

</div>

</div>


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-flask"></i>

</div>


<div>

<h3>

Laboratory Investigation

</h3>


<p>

Test ordering, sample collection, laboratory processing, result generation and review.

</p>

</div>

</div>


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-x-ray"></i>

</div>


<div>

<h3>

Radiology / Diagnostic Imaging

</h3>


<p>

Investigation request, scheduling, imaging, reporting and result communication.

</p>

</div>

</div>


<div class="pathway_card">

<div class="pathway_icon">

<i class="fa-solid fa-person-walking-arrow-right"></i>

</div>


<div>

<h3>

Discharge & Follow-up

</h3>


<p>

Discharge assessment, documentation, medication instructions and follow-up planning.

</p>

</div>

</div>


</div>


</div>


<!-- =========================================
     EHR & DEVICES
     ========================================= -->

<div

id="ehr"

class="settings_section">


<h2>

<i class="fa-solid fa-plug"></i>

EHR & Devices

</h2>


<p>

Manage and monitor integration points between CAREFLOW and external hospital information systems.

</p>


<hr>


<div class="ehr_container">


<div class="ehr_card">


<div class="ehr_icon">

<i class="fa-solid fa-hospital"></i>

</div>


<div class="ehr_details">

<h3>

Hospital EHR System

</h3>

<p>

Electronic Health Record integration

</p>


<span class="ehr_status">

<i class="fa-solid fa-circle"></i>

Not Connected

</span>

</div>

</div>


<div class="ehr_card">


<div class="ehr_icon">

<i class="fa-solid fa-flask"></i>

</div>


<div class="ehr_details">

<h3>

Laboratory Information System

</h3>

<p>

Laboratory orders and result exchange

</p>


<span class="ehr_status">

<i class="fa-solid fa-circle"></i>

Not Connected

</span>

</div>

</div>


<div class="ehr_card">


<div class="ehr_icon">

<i class="fa-solid fa-x-ray"></i>

</div>


<div class="ehr_details">

<h3>

Radiology / PACS

</h3>

<p>

Diagnostic imaging and reporting integration

</p>


<span class="ehr_status">

<i class="fa-solid fa-circle"></i>

Not Connected

</span>

</div>

</div>


<div class="ehr_card">


<div class="ehr_icon">

<i class="fa-solid fa-pills"></i>

</div>


<div class="ehr_details">

<h3>

Pharmacy System

</h3>

<p>

Medication and dispensing information exchange

</p>


<span class="ehr_status">

<i class="fa-solid fa-circle"></i>

Not Connected

</span>

</div>

</div>


<div class="ehr_card">


<div class="ehr_icon">

<i class="fa-solid fa-heart-pulse"></i>

</div>


<div class="ehr_details">

<h3>

Medical Devices

</h3>


<p>

Clinical device integration and monitoring

</p>


<span class="ehr_status">

<i class="fa-solid fa-circle"></i>

Not Integrated

</span>

</div>

</div>


</div>


<br>


<p>

<strong>

Integration Status:

</strong>

External system integration is currently not configured.

</p>


<p>

The integration layer can be implemented in the future using appropriate healthcare interoperability standards such as HL7 FHIR and DICOM.

</p>


</div>


<!-- =========================================
     SECURITY & LOGS
     ========================================= -->

<div

id="security"

class="settings_section">


<h2>

<i class="fa-solid fa-shield-halved"></i>

Security & Logs

</h2>


<p>

Manage account security, access control and system activity monitoring.

</p>


<hr>


<div class="security_container">


<!-- ACCOUNT SECURITY -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-user-shield"></i>

</div>


<div class="security_details">

<h3>

Account Security

</h3>


<p>

Your CAREFLOW account is currently active and protected by role-based access control.

</p>


<span class="security_status">

<i class="fa-solid fa-circle"></i>

Account Active

</span>


</div>

</div>


<!-- LOGIN SESSION -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-right-to-bracket"></i>

</div>


<div class="security_details">

<h3>

Current Login Session

</h3>


<p>

This session is authenticated using your CAREFLOW user account.

</p>


<span class="security_status">

<i class="fa-solid fa-circle"></i>

Session Active

</span>


</div>

</div>


<!-- ACCESS CONTROL -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-lock"></i>

</div>


<div class="security_details">

<h3>

Role-Based Access Control

</h3>


<p>

System features are restricted according to the user's assigned role.

</p>


<span class="security_status">

<i class="fa-solid fa-circle"></i>

Enabled

</span>


</div>

</div>


<!-- AUDIT LOG -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-file-shield"></i>

</div>


<div class="security_details">

<h3>

Audit Logging

</h3>


<p>

System activity logging can be implemented to record important user and administrative actions.

</p>


<span class="security_status">

<i class="fa-solid fa-circle"></i>

Not Configured

</span>


</div>

</div>


<!-- PASSWORD SECURITY -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-key"></i>

</div>


<div class="security_details">

<h3>

Password Security

</h3>


<p>

User passwords should be stored using secure password hashing rather than plain-text storage.

</p>


<span class="security_status">

<i class="fa-solid fa-circle"></i>

Security Recommended

</span>


</div>

</div>


<!-- CHANGE PASSWORD -->

<div class="security_card">


<div class="security_icon">

<i class="fa-solid fa-key"></i>

</div>


<div class="security_details">


<h3>

Change Password

</h3>


<p>

Update your CAREFLOW account password.

</p>


<?php

if(!$password_verified)

{

?>


<form method="POST">


<input

type="password"

name="current_password"

placeholder="Enter current password"

required>


<br><br>


<input

type="submit"

name="verify_current_password"

value="Verify Current Password">


</form>


<?php

}

?>


<?php

if($password_message != "")

{

?>


<p>


<strong>

<?php

echo htmlspecialchars($password_message);

?>


</strong>


</p>


<?php

}

?>


<?php

if($password_verified)

{

?>


<form method="POST">


<input

type="password"

name="new_password"

placeholder="Enter new password"

required>


<br><br>


<input

type="password"

name="repeat_password"

placeholder="Repeat new password"

required>


<br><br>


<input

type="submit"

name="save_new_password"

value="Save New Password">


</form>


<?php

}

?>


</div>

</div>


</div>


<br>


<p>

<strong>

Security Role:

</strong>

<?php

echo htmlspecialchars($current_user["role"]);

?>

</p>


<p>

<strong>

System Access:

</strong>

Role-based access is currently applied to the Settings module.

</p>


</div>


<?php

}

?>


</div>


</div>


</div>


<!-- =========================================
     SETTINGS JAVASCRIPT
     ========================================= -->

<script>


function showSettings(section, button)

{

    let sections =
    document.querySelectorAll(".settings_section");


    let tabs =
    document.querySelectorAll(".settings_tab");


    sections.forEach(function(item)

    {

        item.classList.remove("active");

    });


    tabs.forEach(function(item)

    {

        item.classList.remove("active");

    });


    document.getElementById(section)
    .classList.add("active");


    button.classList.add("active");

}


</script>


<?php

include("../includes/coconut_footer.php");

?>


</body>

</html>

