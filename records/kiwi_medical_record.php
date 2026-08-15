
<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Doctor",
    "Nurse"
]);

include("../database/peach_database.php");

$message="";


if(isset($_POST["save_record"]))
{


$patient_id=$_POST["patient_id"];

$diagnosis=$_POST["diagnosis"];

$treatment=$_POST["treatment"];

$medicine=$_POST["medicine"];



$sql="INSERT INTO medical_record

(patient_id,diagnosis,treatment,medicine)

VALUES

('$patient_id',
'$diagnosis',
'$treatment',
'$medicine')";



if(mysqli_query($connection,$sql))
{

$message="Medical Record Added Successfully";

}

else

{

$message="Failed To Add Record";

}


}


?>



<?php

include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");

?>


<h2 class="page_title">

📋 Medical Records

</h2>



<div class="form_box">


<h2>

Add Medical Record

</h2>



<form method="POST">


<label>

Select Patient

</label>


<select name="patient_id">


<?php


$patients=mysqli_query(

$connection,

"SELECT * FROM patient"

);



while($p=mysqli_fetch_assoc($patients))

{


?>


<option value="<?php echo $p['patient_id']; ?>">


<?php echo $p['patient_name']; ?>


</option>


<?php

}

?>


</select>







<label>

Diagnosis

</label>


<textarea

name="diagnosis"

required>

</textarea>




<label>

Treatment

</label>


<textarea

name="treatment"

required>

</textarea>




<label>

Medicine

</label>


<textarea

name="medicine"

required>

</textarea>




<input

type="submit"

name="save_record"

value="Save Record">


</form>



<h3>

<?php echo $message; ?>

</h3>


</div>
<hr>


<h2 style="text-align:center">

Medical Record List

</h2>



<table border="1" width="95%" align="center">


<tr>

<th>ID</th>

<th>Patient</th>


<th>Diagnosis</th>

<th>Treatment</th>

<th>Medicine</th>
<th>Edit</th>

<th>Delete</th>

</tr>



<?php


$sql="SELECT

medical_record.record_id,

patient.patient_name,

medical_record.diagnosis,

medical_record.treatment,

medical_record.medicine


FROM medical_record


INNER JOIN patient

ON medical_record.patient_id = patient.patient_id";



$result=mysqli_query($connection,$sql);



while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["record_id"]; ?>

</td>



<td>

<?php echo $row["patient_name"]; ?>

</td>





<td>

<?php echo $row["diagnosis"]; ?>

</td>



<td>

<?php echo $row["treatment"]; ?>

</td>



<td>

<?php echo $row["medicine"]; ?>

</td>

<td>

<a href="lime_edit_record.php?id=<?php echo $row['record_id']; ?>">

Edit

</a>

</td>


<td>

<a 

href="raspberry_delete_record.php?id=<?php echo $row['record_id']; ?>"

onclick="return confirm('Delete this medical record?')">


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