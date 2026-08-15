
<?php

session_start();

include("database/peach_database.php");

$message = "";

$show_reset_form = false;

$user_id = "";


/* =========================================
   FIND ACCOUNT
   ========================================= */

if(isset($_POST["find_account"]))
{

    $work_email = trim($_POST["work_email"]);


    $stmt = mysqli_prepare(
        $connection,
        "SELECT user_id, full_name, status
         FROM user_login
         WHERE work_email=?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $work_email
    );


    mysqli_stmt_execute($stmt);


    $result = mysqli_stmt_get_result($stmt);


    if(mysqli_num_rows($result) > 0)
    {

        $user = mysqli_fetch_assoc($result);


        if($user["status"] == "Pending")
        {

            $message =
            "Your account is still pending administrator approval.";

        }

        else if($user["status"] == "Rejected")
        {

            $message =
            "Your account has been rejected. Please contact the hospital administrator.";

        }

        else
        {

            $_SESSION["reset_user_id"] =
            $user["user_id"];


            $show_reset_form = true;

        }

    }

    else
    {

        $message =
        "No account was found with this work email.";

    }

}



/* =========================================
   UPDATE PASSWORD
   ========================================= */

if(isset($_POST["reset_password"]))
{

    if(!isset($_SESSION["reset_user_id"]))
    {

        $message =
        "Password reset session expired. Please try again.";

    }

    else
    {

        $user_id =
        $_SESSION["reset_user_id"];


        $new_password =
        $_POST["new_password"];


        $confirm_password =
        $_POST["confirm_password"];


        if($new_password != $confirm_password)
        {

            $message =
            "Passwords do not match.";

            $show_reset_form = true;

        }

        else if(strlen($new_password) < 8)
        {

            $message =
            "Password must contain at least 8 characters.";

            $show_reset_form = true;

        }

        else
        {

            $hashed_password =
            password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );


            $stmt = mysqli_prepare(
                $connection,
                "UPDATE user_login
                 SET password=?
                 WHERE user_id=?"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $hashed_password,
                $user_id
            );


            if(mysqli_stmt_execute($stmt))
            {

                unset($_SESSION["reset_user_id"]);


                $message =
                "Password updated successfully. You can now log in.";

                $show_reset_form = false;

            }

            else
            {

                $message =
                "Password update failed. Please try again.";

                $show_reset_form = true;

            }

        }

    }

}

?>


<!DOCTYPE html>

<html>


<head>


<meta charset="UTF-8">


<title>
Forgot Password | CAREFLOW
</title>


<link rel="stylesheet"
href="css/watermelon_style.css">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>



<body>



<div class="top_header">


<div class="logo_section">


<img
src="images/logo.png"
class="logo"
>


<div>


<h1>
CAREFLOW
</h1>


<p>
Hospital Management System
</p>


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


<i class="fa-solid fa-key"></i>


Forgot Password


</h2>



<?php

if($message != "")
{

?>

<h3 style="text-align:center;">

<?php

echo htmlspecialchars($message);

?>

</h3>

<?php

}

?>



<?php

if(!$show_reset_form)

{

?>



<!-- =========================================
     FIND ACCOUNT FORM
     ========================================= -->


<form method="POST">


<label>

Registered Work Email

</label>


<input

type="email"

name="work_email"

placeholder="Enter your registered work email"

required>


<input

type="submit"

name="find_account"

value="Find Account">


</form>



<?php

}

else

{

?>



<!-- =========================================
     RESET PASSWORD FORM
     ========================================= -->


<form method="POST">


<h3>

Set New Password

</h3>


<label>

New Password

</label>


<input

type="password"

name="new_password"

placeholder="Minimum 8 characters"

required>


<label>

Confirm New Password

</label>


<input

type="password"

name="confirm_password"

placeholder="Re-enter new password"

required>


<input

type="submit"

name="reset_password"

value="Save New Password">


</form>



<?php

}

?>



<p style="text-align:center; margin-top:20px;">


<a href="avocado_login.php">

<i class="fa-solid fa-arrow-left"></i>

Back to Login

</a>


</p>



</div>



</body>


</html>

