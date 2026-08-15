<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Billing"
]);

include("../database/peach_database.php");

require("../vendor/autoload.php");

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


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
   CREATE WORD DOCUMENT
   ========================================= */

$phpWord = new PhpWord();

$section = $phpWord->addSection();


/* TITLE */

$section->addText(
    "CAREFLOW",
    array(
        "bold" => true,
        "size" => 18
    ),
    array(
        "alignment" => "center"
    )
);


$section->addText(
    "Billing Report",
    array(
        "bold" => true,
        "size" => 14
    ),
    array(
        "alignment" => "center",
        "spaceAfter" => 300
    )
);


/* =========================================
   CREATE TABLE
   ========================================= */

$table = $section->addTable(
    array(
        "borderSize" => 6,
        "borderColor" => "555555",
        "cellMargin" => 80
    )
);


/* HEADER */

$table->addRow();

$table->addCell(1200)->addText(
    "Bill ID",
    array("bold" => true)
);

$table->addCell(3000)->addText(
    "Patient Name",
    array("bold" => true)
);

$table->addCell(1800)->addText(
    "Amount",
    array("bold" => true)
);

$table->addCell(2200)->addText(
    "Payment Method",
    array("bold" => true)
);


/* =========================================
   BILLING DATA
   ========================================= */

while ($row = mysqli_fetch_assoc($result))
{

    $table->addRow();

    $table->addCell(1200)->addText(
        htmlspecialchars($row["bill_id"])
    );

    $table->addCell(3000)->addText(
        htmlspecialchars($row["patient_name"])
    );

    $table->addCell(1800)->addText(
        "Rs. " . htmlspecialchars($row["amount"])
    );

    $table->addCell(2200)->addText(
        htmlspecialchars($row["payment_method"])
    );

}


/* =========================================
   DOWNLOAD DOCX
   ========================================= */

$filename = "billing_report.docx";

header(
    "Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document"
);

header(
    "Content-Disposition: attachment; filename=" . $filename
);

header(
    "Cache-Control: max-age=0"
);


$writer = IOFactory::createWriter(
    $phpWord,
    "Word2007"
);

$writer->save("php://output");

exit();

?>