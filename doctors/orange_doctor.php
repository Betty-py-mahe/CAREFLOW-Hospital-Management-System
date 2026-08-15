<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor"
]);

include("../database/peach_database.php");


$message="";


if(isset($_POST["save_doctor"]))
{


$name=$_POST["doctor_name"];

$specialization=$_POST["specialization"];


$phone=$_POST["phone"];

$email=$_POST["email"];



$stmt = mysqli_prepare(
    $connection,
    "INSERT INTO doctor
    (doctor_name, specialization, phone, email)
    VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $name,
    $specialization,
    $phone,
    $email
);

if(mysqli_stmt_execute($stmt))
{

$message="Doctor Added Successfully";

}

else

{

$message="Doctor Addition Failed";

}


}


?>

<?php

include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");

?>


<h2 class="page_title">

🩺 Doctor Management

</h2>

<div class="form_box">


<h2>

Register Doctor

</h2>



<form method="POST">


<label>

Doctor Name

</label>


<input

type="text"

name="doctor_name"

required>



<label>

Specialization

</label>


<input

type="text"

name="specialization"

placeholder="Example: Cardiology"

required>




<label>

Email

</label>


<input

type="email"

name="email"

required>



<label>

Phone

</label>


<input

type="text"

name="phone"

required>




<input

type="submit"

name="save_doctor"

value="Save Doctor">


</form>



<h3>

<?php echo $message; ?>

</h3>


</div>

<hr>


<h2 style="text-align:center">

Doctor List

</h2>






<br>



<table border="1" width="90%" align="center">


<tr>


<th>ID</th>

<th>Name</th>

<th>Specialization</th>

<th>Email</th>

<th>Phone</th>

<th>Edit</th>

<th>Delete</th>


</tr>


<form method="GET">

<input

type="text"

name="search"

placeholder="Search Doctor"

class="search_box">

<br><br>

<input

type="submit"

value="Search">

</form>

<br>


<?php

if(isset($_GET['search']))
{

    $search = $_GET['search'];

    $search_value = "%" . $search . "%";


    $stmt = mysqli_prepare(
        $connection,
        "SELECT * FROM doctor
         WHERE doctor_name LIKE ?
         OR specialization LIKE ?
         OR phone LIKE ?
         OR email LIKE ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $search_value,
        $search_value,
        $search_value,
        $search_value
    );


    mysqli_stmt_execute($stmt);


    $result = mysqli_stmt_get_result($stmt);

}

else

{

    $query = "SELECT * FROM doctor";

    $result = mysqli_query($connection,$query);

}


while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["doctor_id"]; ?>

</td>



<td>

<?php echo $row["doctor_name"]; ?>

</td>



<td>

<?php echo $row["specialization"]; ?>

</td>



<td>

<?php echo $row["email"]; ?>

</td>



<td>

<?php echo $row["phone"]; ?>

</td>



<td>

<a href="plum_edit_doctor.php?id=<?php echo $row["doctor_id"]; ?>">

Edit

</a>

</td>



<td>


<a 

href="pear_delete_doctor.php?id=<?php echo $row["doctor_id"]; ?>"

onclick="return confirm('Delete this doctor?')">


Delete


</a>


</td>


</tr>


<?php

}

?>


</table>


<?php

include("../includes/coconut_footer.php");

?>