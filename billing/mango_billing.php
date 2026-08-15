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



<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:92%;
    margin:30px auto 24px auto;
">

    <h2 class="page_title" style="margin:0;">

        💳 Billing Management

    </h2>


    <div style="
        display:flex;
        gap:10px;
        align-items:center;
    ">


        <!-- REPORT -->

        <a
        href="../reports/melon_billing_report.php"
        style="
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:9px 15px;
            background:white;
            border:1px solid lightgray;
            border-radius:6px;
            color:darkslateblue;
            text-decoration:none;
            font-family:Georgia, 'Times New Roman', serif;
            font-size:14px;
            font-weight:600;
        "
        >

            <i class="fa-solid fa-file-invoice"></i>

            Report

        </a>


        <!-- DOWNLOAD -->

        <div style="position:relative;">

            <button
            type="button"
            onclick="toggleBillingDownload()"
            style="
                display:inline-flex;
                align-items:center;
                gap:7px;
                padding:9px 15px;
                background:white;
                border:1px solid lightgray;
                border-radius:6px;
                color:darkslateblue;
                font-family:Georgia, 'Times New Roman', serif;
                font-size:14px;
                font-weight:600;
                cursor:pointer;
            "
            >

                <i class="fa-solid fa-download"></i>

                Download

                <i class="fa-solid fa-chevron-down"></i>

            </button>


            <div
            id="billingDownloadMenu"
            style="
                display:none;
                position:absolute;
                right:0;
                top:42px;
                min-width:150px;
                background:white;
                border:1px solid lightgray;
                border-radius:6px;
                box-shadow:0 4px 12px rgba(0,0,0,0.15);
                z-index:1000;
            "
            >

                <a
                href="../reports/melon_billing_report.php?download=csv"
                style="
                    display:block;
                    padding:10px 14px;
                    color:darkslategray;
                    text-decoration:none;
                "
                >

                    <i class="fa-solid fa-file-csv"></i>

                    CSV

                </a>


                <a
                href="../downloads/billing_pdf.php"
                style="
                    display:block;
                    padding:10px 14px;
                    color:darkslategray;
                    text-decoration:none;
                "
                >

                    <i class="fa-solid fa-file-pdf"></i>

                    PDF

                </a>


                <a
                <a
                href="../downloads/billing_docx.php"
                style="
                    display:block;
                    padding:10px 14px;
                    color:darkslategray;
                    text-decoration:none;
                "
                >

                    <i class="fa-solid fa-file-word"></i>

                    DOCX

                </a>

            </div>

        </div>

    </div>

</div>


<script>

function toggleBillingDownload()
{
    var menu =
        document.getElementById(
            "billingDownloadMenu"
        );

    if(menu.style.display === "none")
    {
        menu.style.display = "block";
    }
    else
    {
        menu.style.display = "none";
    }
}

</script>




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