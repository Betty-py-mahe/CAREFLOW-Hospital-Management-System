
<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor",
    "Nurse",
    "Reception"
]);

include("../database/peach_database.php");

$message="";


if(isset($_POST["save_patient"]))
{


$name=$_POST["patient_name"];

$gender=$_POST["gender"];

$age=$_POST["age"];

$phone=$_POST["phone"];

$address=$_POST["address"];



$stmt = mysqli_prepare(
    $connection,
    "INSERT INTO patient
    (patient_name, gender, age, phone, address, registration_date)
    VALUES (?, ?, ?, ?, ?, CURDATE())"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssiss",
    $name,
    $gender,
    $age,
    $phone,
    $address
);

if(mysqli_stmt_execute($stmt))
{

$message="Patient Added Successfully";

}

else

{

$message="Patient Addition Failed";

}


}


?>


<?php

include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");

?>

<h2 class="page_title">

👤 Patient Management

</h2>



<div class="form_box">


<h2>

Register Patient

</h2>



<form method="POST">


<label>

Patient Name

</label>


<input

type="text"

name="patient_name"

required>



<label>

Gender

</label>


<select name="gender">


<option>

Male

</option>


<option>

Female

</option>


<option>

Other

</option>


</select>




<label>

Age

</label>


<input

type="number"

name="age"

required>




<label>

Phone

</label>


<input

type="text"

name="phone"

required>




<label>

Address

</label>


<textarea

name="address"

required>

</textarea>




<input

type="submit"

name="save_patient"

value="Save Patient">


</form>



<h3>

<?php echo $message; ?>

</h3>


</div>


<hr>

<br>
<h2 style="text-align:center">

Patient List

</h2>


<?php

echo date("Y-m-d");

?>
<table border="1" width="90%" align="center">


<tr>

<th>ID</th>

<th>Name</th>

<th>Gender</th>

<th>Age</th>

<th>Phone</th>

<th>Address</th>
<th>Registration Date</th>
<th>Edit</th>

<th>Delete</th>


</tr>



<form method="GET">

<input
type="text"
name="search"
placeholder="Search by Name or Phone"
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
    "SELECT * FROM patient
     WHERE patient_name LIKE ?
     OR phone LIKE ?
     OR address LIKE ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $search_value,
    $search_value,
    $search_value
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

}

else
{

$query="SELECT * FROM patient";

$result=mysqli_query($connection,$query);


}


while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["patient_id"]; ?>

</td>



<td>

<?php echo $row["patient_name"]; ?>

</td>



<td>

<?php echo $row["gender"]; ?>

</td>



<td>

<?php echo $row["age"]; ?>

</td>



<td>

<?php echo $row["phone"]; ?>

</td>



<td>

<?php echo $row["address"]; ?>

</td>

<td>

<?php echo $row["registration_date"]; ?>

</td>

<td>


<a href="cherry_edit_patient.php?id=<?php echo $row["patient_id"]; ?>">

Edit

</a>


</td>



<td>


<a 

href="guava_delete_patient.php?id=<?php echo $row["patient_id"]; ?>"

onclick="return confirm('Are you sure you want to delete this patient?')">

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