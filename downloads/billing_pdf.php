<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Billing"
]);

include("../database/peach_database.php");

require("../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;


/* =========================================
   GET BILLING DATA
   ========================================= */

$sql = "SELECT

billing.bill_id,
patient.patient_name,
billing.amount,
billing.payment_method

FROM billing

INNER JOIN patient

ON billing.patient_id = patient.patient_id

ORDER BY billing.bill_id DESC";


$result = mysqli_query($connection, $sql);


if (!$result)
{
    die("Unable to retrieve billing records.");
}


/* =========================================
   CREATE PDF HTML
   ========================================= */

$html = '

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<style>

body
{
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
}

h1
{
    text-align: center;
    margin-bottom: 5px;
}

.subtitle
{
    text-align: center;
    margin-bottom: 20px;
}

table
{
    width: 100%;
    border-collapse: collapse;
}

th
{
    background-color: #eeeeee;
    border: 1px solid #555;
    padding: 8px;
    text-align: left;
}

td
{
    border: 1px solid #555;
    padding: 8px;
}

.amount
{
    text-align: right;
}

</style>

</head>

<body>

<h1>CAREFLOW</h1>

<div class="subtitle">

Billing Report

</div>


<table>

<tr>

<th>Bill ID</th>

<th>Patient Name</th>

<th>Amount</th>

<th>Payment Method</th>

</tr>
';


/* =========================================
   ADD BILLING RECORDS
   ========================================= */

while ($row = mysqli_fetch_assoc($result))
{

    $html .= '

    <tr>

    <td>'
    . htmlspecialchars($row["bill_id"])
    . '</td>

    <td>'
    . htmlspecialchars($row["patient_name"])
    . '</td>

    <td class="amount">
    ₹ '
    . htmlspecialchars($row["amount"])
    . '</td>

    <td>'
    . htmlspecialchars($row["payment_method"])
    . '</td>

    </tr>

    ';

}


$html .= '

</table>

</body>

</html>
';


/* =========================================
   CREATE PDF
   ========================================= */

$options = new Options();

$options->set(
    "isRemoteEnabled",
    true
);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper(
    "A4",
    "portrait"
);

$dompdf->render();


/* =========================================
   DOWNLOAD PDF
   ========================================= */

$dompdf->stream(
    "billing_report.pdf",
    array(
        "Attachment" => true
    )
);

exit();

?>