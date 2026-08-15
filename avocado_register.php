<?php

session_start();

include("database/peach_database.php");

$message = "";


if(isset($_POST["register"]))
{

    $full_name = $_POST["full_name"];

    $work_email = $_POST["work_email"];

    $phone = $_POST["phone"];

    $employee_id = $_POST["employee_id"];

    $role = $_POST["role"];

    $department = $_POST["department"];

    $medical_license = $_POST["medical_license"];

    $password = $_POST["password"];

    $confirm_password = $_POST["confirm_password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    /* Check password */

    if($password != $confirm_password)
    {
        $message = "Passwords do not match.";
    }

    else
    {

        /* Check whether employee ID already exists */

        $check = mysqli_query(
            $connection,
            "SELECT * FROM user_login
             WHERE employee_id='$employee_id'
             OR username='$work_email'"
        );


        if(mysqli_num_rows($check) > 0)
        {
            $message = "An account with this Employee ID or Email already exists.";
        }

        else
        {

            $sql = "INSERT INTO user_login

            (username,password,full_name,work_email,phone,
            employee_id,role,department,medical_license,status)

            VALUES

            ('$work_email',
            '$hashed_password',
            '$full_name',
            '$work_email',
            '$phone',
            '$employee_id',
            '$role',
            '$department',
            '$medical_license',
            'Pending')";


            if(mysqli_query($connection,$sql))
            {
                $message = "Registration submitted successfully. Your account is pending administrator approval.";
            }

            else
            {
                $message = "Registration failed. Please try again.";
            }

        }

    }

}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Staff Registration | CAREFLOW</title>

<link rel="stylesheet" href="css/watermelon_style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<div class="top_header">

<div class="logo_section">

<img src="images/logo.png" class="logo">

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

</div>

</div>



<div class="form_box">


<h2>

<i class="fa-solid fa-user-plus"></i>

Create Staff Account

</h2>


<p style="text-align:center; margin-bottom:25px;">

Already have an account?

<a href="avocado_login.php">

Log In

</a>

</p>



<form method="POST">


<label>

Full Name *

</label>

<input

type="text"

name="full_name"

placeholder="Enter full name"

required>



<label>

Work Email Address *

</label>

<input

type="email"

name="work_email"

placeholder="doctor@hospital.com"

required>



<label>

Phone Number *

</label>

<input

type="text"

name="phone"

placeholder="Enter phone number"

required>



<label>

Employee / Staff ID *

</label>

<input

type="text"

name="employee_id"

placeholder="Enter employee ID"

required>



<label>

System Role *

</label>

<select name="role" id="role" required>

<option value="">

Select Role

</option>

<option value="Doctor">

Doctor / Physician

</option>

<option value="Nurse">

Nurse

</option>

<option value="Reception">

Front Desk / Reception

</option>

<option value="Billing">

Billing Staff

</option>

<option value="Pharmacist">

Pharmacist

</option>

<option value="Lab Technician">

Lab Technician

</option>

</select>



<label>

Department *

</label>

<select name="department" required>

<option value="">

Select Department

</option>

<option value="Cardiology">

Cardiology

</option>

<option value="Emergency">

Emergency

</option>

<option value="Outpatient">

Outpatient

</option>

<option value="Accounts">

Accounts

</option>

<option value="Pharmacy">

Pharmacy

</option>

<option value="Laboratory">

Laboratory

</option>

<option value="General">

General

</option>

</select>



<label>

Medical License / Council Registration Number

</label>

<input

type="text"

name="medical_license"

placeholder="Required for doctors">



<label>

Password *

</label>

<input

type="password"

name="password"

placeholder="Minimum 8 characters"

required>



<label>

Confirm Password *

</label>

<input

type="password"

name="confirm_password"

placeholder="Re-enter password"

required>



<label style="font-weight:normal;">

<input

type="checkbox"

name="security_agreement"

required

style="width:auto;">

I agree to the Hospital Data Security & Privacy Policy.

</label>



<input

type="submit"

name="register"

value="Request Account Access">



<p style="text-align:center; margin-top:15px;">

New accounts require administrator verification before access is granted.

</p>


<h3 style="text-align:center;">

<?php echo $message; ?>

</h3>


</form>


</div>


</body>

</html>