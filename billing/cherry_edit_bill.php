<?php

session_start();

include("../includes/coconut_access.php");

requireRole([
    "System Administrator",
    "Billing Staff"
]);

include("../database/peach_database.php");

$id = $_GET["id"] ?? '';

if($id == '')
{
    header("Location:mango_billing.php");
    exit();
}


/* Get bill details */

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM billing WHERE bill_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$bill = mysqli_fetch_assoc($result);


/* Check bill exists */

if(!$bill)
{
    header("Location:mango_billing.php");
    exit();
}


/* Update bill */

if(isset($_POST["update_bill"]))
{
    $amount = $_POST["amount"];

    $payment_method = $_POST["payment_method"];


    $stmt = mysqli_prepare(
        $connection,
        "UPDATE billing
         SET amount=?,
             payment_method=?
         WHERE bill_id=?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "dsi",
        $amount,
        $payment_method,
        $id
    );


    if(mysqli_stmt_execute($stmt))
    {
        header("Location:mango_billing.php");
        exit();
    }
}

?>
<!DOCTYPE html>


<html>

<head>

<meta charset="UTF-8">

<title>Edit Bill | CAREFLOW</title>

<link rel="stylesheet" href="../css/watermelon_style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<div class="top_header">

<div class="logo_section">

<img src="../images/logo.png" class="logo">

<div>

<h1>CAREFLOW</h1>

<p>Hospital Management System</p>

</div>

</div>


<div class="header_right">

<div class="system_status">

<i class="fa-solid fa-circle"></i>

System Online

</div>

<div class="admin_name">

<i class="fa-solid fa-user-shield"></i>

Administrator

</div>

</div>

</div>



<div class="form_box">


<h2>

<i class="fa-solid fa-file-invoice-dollar"></i>

Edit Bill

</h2>


<form method="POST">


<label>

Amount

</label>


<input

type="number"

name="amount"

min="0"

value="<?php echo $bill['amount']; ?>"

required>



<label>

Payment Method

</label>


<select name="payment_method" required>


<option value="Cash"
<?php if($bill['payment_method']=="Cash") echo "selected"; ?>>

Cash

</option>


<option value="Card"
<?php if($bill['payment_method']=="Card") echo "selected"; ?>>

Card

</option>


<option value="UPI"
<?php if($bill['payment_method']=="UPI") echo "selected"; ?>>

UPI

</option>


<option value="Insurance"
<?php if($bill['payment_method']=="Insurance") echo "selected"; ?>>

Insurance

</option>


</select>


<input

type="submit"

name="update_bill"

value="Update Bill">


</form>


</div>


</body>

</html>