<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator"
]);

include("../database/peach_database.php");

$message = "";


/* Approve account */

if(isset($_GET["approve"]))
{
    $id = $_GET["approve"];

    $sql = "UPDATE user_login

            SET status='Active'

            WHERE user_id='$id'";

    if(mysqli_query($connection,$sql))
    {
        $message = "Account approved successfully.";
    }
}


/* Reject account */

if(isset($_GET["reject"]))
{
    $id = $_GET["reject"];

    $sql = "UPDATE user_login

            SET status='Rejected'

            WHERE user_id='$id'";

    if(mysqli_query($connection,$sql))
    {
        $message = "Account rejected.";
    }
}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>User Management | CAREFLOW</title>

<link rel="stylesheet" href="../css/watermelon_style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<div class="top_header">

<div class="logo_section">

<img src="../images/logo.png" class="logo">

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

<div class="admin_name">

<i class="fa-solid fa-user-shield"></i>

System Administrator

</div>

</div>

</div>



<div class="form_box">


<h2>

<i class="fa-solid fa-users-gear"></i>

Staff Account Management

</h2>


<?php

if($message != "")
{

echo "<h3 style='text-align:center;'>$message</h3>";

}

?>


<table>


<tr>

<th>Name</th>

<th>Employee ID</th>

<th>Role</th>

<th>Department</th>

<th>Status</th>

<th>Action</th>

</tr>


<?php


$query = "SELECT *

          FROM user_login

          WHERE role != 'System Administrator'

          ORDER BY user_id DESC";


$result = mysqli_query($connection,$query);


while($row = mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["full_name"]; ?>

</td>


<td>

<?php echo $row["employee_id"]; ?>

</td>


<td>

<?php echo $row["role"]; ?>

</td>


<td>

<?php echo $row["department"]; ?>

</td>


<td>

<?php echo $row["status"]; ?>

</td>


<td>


<?php

if($row["status"] == "Pending")

{

?>


<a

href="apple_user_management.php?approve=<?php echo $row['user_id']; ?>"

class="edit_button"

onclick="return confirm('Approve this account?')">

Approve

</a>


<a

href="apple_user_management.php?reject=<?php echo $row['user_id']; ?>"

class="delete_button"

onclick="return confirm('Reject this account?')">

Reject

</a>


<?php

}

else

{

echo "No Action";

}

?>


</td>


</tr>


<?php

}

?>


</table>


</div>


</body>

</html>