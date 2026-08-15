<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Billing"
]);

include("../database/peach_database.php");


$message="";


if(isset($_POST["save_bill"]))
{


$patient_id=$_POST["patient_id"];

$amount=$_POST["amount"];

$payment_method=$_POST["payment_method"];



$sql="INSERT INTO billing

(patient_id,amount,payment_method)

VALUES

('$patient_id',
'$amount',
'$payment_method')";



if(mysqli_query($connection,$sql))
{

$message="Bill Created Successfully";

}

else

{

$message="Bill Creation Failed";

}


}


?>


<?php

include("../includes/coconut_header.php");

include("../includes/coconut_sidebar.php");

?>


<h2 class="page_title">

💰 Billing Management

</h2>


<div class="form_box">


<h2>

Create Bill

</h2>



<form method="POST">


<label>

Select Patient

</label>


<select name="patient_id" required>


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

Amount

</label>


<input

type="number"

name="amount"

min="0"

required>



<label>

Payment Method

</label>


<select name="payment_method" required>


<option>

Cash

</option>


<option>

Card

</option>


<option>

UPI

</option>


<option>

Insurance

</option>


</select>



<input

type="submit"

name="save_bill"

value="Create Bill">


</form>



<h3>

<?php echo $message; ?>

</h3>


</div>
<hr>


<h2 style="text-align:center">

Billing History

</h2>



<table border="1" width="80%" align="center">


<tr>

<th>Bill ID</th>

<th>Patient</th>

<th>Amount</th>

<th>Payment Method</th>
<th>Edit</th>

<th>Delete</th>

</tr>



<?php


$sql="SELECT

billing.bill_id,

patient.patient_name,

billing.amount,

billing.payment_method


FROM billing


INNER JOIN patient


ON billing.patient_id = patient.patient_id";



$result=mysqli_query($connection,$sql);



while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $row["bill_id"]; ?>

</td>


<td>

<?php echo $row["patient_name"]; ?>

</td>


<td>

₹ <?php echo $row["amount"]; ?>

</td>


<td>

<?php echo $row["payment_method"]; ?>

</td>

<td>

<a href="cherry_edit_bill.php?id=<?php echo $row['bill_id']; ?>">

Edit

</a>

</td>


<td>

<a

href="lemon_delete_bill.php?id=<?php echo $row['bill_id']; ?>"

onclick="return confirm('Delete this bill?')">

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