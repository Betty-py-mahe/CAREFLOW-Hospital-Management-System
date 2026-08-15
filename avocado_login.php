
<?php

session_start();

include("database/peach_database.php");

$message = "";


if(isset($_POST["login"]))
{

    $username = $_POST["username"];

    $password = $_POST["password"];

    $stmt = mysqli_prepare(
        $connection,
        "SELECT * FROM user_login WHERE username = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $username
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    if(mysqli_num_rows($result) > 0)
    {

        $user = mysqli_fetch_assoc($result);


        /* Check account status */

        if($user["status"] == "Pending")
        {
            $message = "Your account is pending administrator approval.";
        }

        else if($user["status"] == "Rejected")
        {
            $message = "Your account has been rejected. Please contact the hospital administrator.";
        }

        /* Check password */

        else if(password_verify($password,$user["password"]))
        {
            session_regenerate_id(true);

            $_SESSION["username"] = $user["username"];

            $_SESSION["user_id"] = $user["user_id"];

            $_SESSION["role"] = $user["role"];

            $_SESSION["full_name"] = $user["full_name"];


            header("Location:dashboard/mango_dashboard.php");

            exit();

        }

        else
        {
            $message = "Invalid Username or Password";
        }

    }

    else
    {
        $message = "Invalid Username or Password";
    }

}

?>

<!DOCTYPE html>

<html>


<head>


<title>
Hospital Login
</title>


<link rel="stylesheet" href="css/watermelon_style.css">


</head>



<body>


<div class="form_box">


<h2>
Hospital Management Login
</h2>



<form method="POST">


<label>
Username
</label>


<input

type="text"

name="username"

required>


<label>
Password
</label>


<input

type="password"

name="password"

required>



<input

type="submit"

name="login"

value="Login">


</form>



<h3>

<?php echo $message; ?>

</h3>



<!-- ACCOUNT OPTIONS -->

<div
style="
display:flex;
justify-content:center;
gap:20px;
margin-top:15px;
"
>


<a
href="banana_forgot_password.php"
style="
text-decoration:none;
"
>

Forgot Password?

</a>


<span>
|
</span>


<a
href="avocado_register.php"
style="
text-decoration:none;
"
>

Register

</a>


</div>



</div>



</body>


</html>
