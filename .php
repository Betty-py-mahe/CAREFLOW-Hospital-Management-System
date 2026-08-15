<?php

include("database/peach_database.php");

$password = "admin123";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE user_login SET

password='$hashed_password',

full_name='System Administrator',

work_email='admin@careflow.com',

phone='',

employee_id='ADMIN001',

role='System Administrator',

department='Administration',

medical_license=NULL,

status='Active'

WHERE user_id=1";


if(mysqli_query($connection,$sql))
{
    echo "Administrator account updated successfully.";
}
else
{
    echo "Failed to update administrator account.";
}

?>
