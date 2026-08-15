
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


<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:92%;
    margin:30px auto 24px auto;
">

    <h2 class="page_title" style="margin:0;">

        🩺 Medical Record Management

    </h2>


    <div style="
        display:flex;
        gap:10px;
        align-items:center;
    ">


        <!-- REPORT -->

        <a
        href="../reports/melon_medical_record_report.php"
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

            <i class="fa-solid fa-file-medical"></i>

            Report

        </a>


        <!-- DOWNLOAD -->

        <div style="position:relative;">

            <button
            type="button"
            onclick="toggleMedicalDownload()"
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
            id="medicalDownloadMenu"
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
                href="../reports/melon_medical_record_report.php?download=csv"
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
                href="../downloads/medical_record_pdf.php"
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
                href="../downloads/medical_record_docx.php"
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

function toggleMedicalDownload()
{
    var menu =
        document.getElementById(
            "medicalDownloadMenu"
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