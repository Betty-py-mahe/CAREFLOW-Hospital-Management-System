<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>CAREFLOW Hospital Management System</title>

<link rel="stylesheet"
href="/HospitalProject_V2/css/watermelon_style.css?v=3">

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

<?php echo htmlspecialchars($_SESSION["full_name"]); ?>

</div>



<div class="today_date">

<i class="fa-solid fa-calendar"></i>

<?php echo date("d F Y"); ?>

</div>




<!-- SETTINGS BUTTON -->

<a
href="/HospitalProject_V2/settings/papaya_settings.php"
class="settings_button"
style="
display:inline-flex;
align-items:center;
justify-content:center;
gap:6px;
padding:7px 12px;
margin:0;
width:auto;
height:auto;
font-family:Georgia, 'Times New Roman', serif;
font-size:14px;
font-weight:600;
line-height:1;
text-decoration:none;
border:1px solid currentColor;
border-radius:6px;
cursor:pointer;
box-sizing:border-box;
"
>

<i class="fa-solid fa-gear"></i>

Settings

</a>


</div>

</div>