<?php

if(isset($_GET["today"]))
{

$sql="SELECT

appointment.appointment_id,

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time

FROM appointment

INNER JOIN patient

ON appointment.patient_id = patient.patient_id

INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id

WHERE appointment.appointment_date = CURDATE()

ORDER BY appointment.appointment_time";

}

elseif(isset($_GET["search"]))
{

$search=$_GET["search"];

$date=$_GET["date_search"];

$sql="SELECT

appointment.appointment_id,

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time

FROM appointment

INNER JOIN patient

ON appointment.patient_id = patient.patient_id

INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id

WHERE

patient.patient_name LIKE '%$search%'

OR doctor.doctor_name LIKE '%$search%'

OR appointment.appointment_date LIKE '%$date%'

ORDER BY appointment.appointment_date DESC";

}

else
{

$sql="SELECT

appointment.appointment_id,

patient.patient_name,

doctor.doctor_name,

appointment.appointment_date,

appointment.appointment_time

FROM appointment

INNER JOIN patient

ON appointment.patient_id = patient.patient_id

INNER JOIN doctor

ON appointment.doctor_id = doctor.doctor_id

ORDER BY appointment.appointment_date DESC";

}

$result=mysqli_query($connection,$sql);

?>

while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["appointment_id"]; ?>

</td>


<td>

<?php echo $row["patient_name"]; ?>

</td>


<td>

<?php echo $row["doctor_name"]; ?>

</td>


<td>

<?php echo $row["appointment_date"]; ?>

</td>


<td>

<?php echo $row["appointment_time"]; ?>
</td>


<td>

<a href="melon_edit_appointment.php?id=<?php echo $row['appointment_id']; ?>">

Edit

</a>


</td>



<td>


<a 

href="fig_delete_appointment.php?id=<?php echo $row['appointment_id']; ?>"

onclick="return confirm('Delete this appointment?')">


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