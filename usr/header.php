<?php 
session_start();
require '../vendor/autoload.php';
include('../lib/crypto.php');
include('../lib/option.php');
include('../lib/qrcode.php');
include('../lib/jwthandler.php');
if(!isset($_GET["sid"]) || !isset($_SESSION["student_information"]) || !isset($_SESSION["token"])){
    session_unset();
    session_destroy();
    header('location:../');
    exit();
} 
else { $decryptedId = Crypto::Decrypt($_GET["sid"]); }

if(!JwtHandler::gatewayInit($_SESSION["token"])) {
    session_unset();
    session_destroy();
    header('location: ../?message=Token expired. Please login again');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="../lib/tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <link rel="icon" type="image/png" href="../img/ssaam-new.png">
    <title>SSAAM</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
*{font-family:"Poppins",sans-serif;font-weight:400;font-style:normal;}
body{height:100%;margin:0;padding:0;background-color:#eeeeee !important;}
body::-webkit-scrollbar{width:0px;background:transparent;}
#main-layout-container{margin:20px;height:100%;}
.btn-primary{background-color:#080F5B!important;border-color:#080F5B!important;}
.btn-primary:hover{background-color:#0d1889 !important;border-color:#0d1889 !important;}
</style>
<body>
<?php include 'navigation.php';?>
<div id="main-layout-container">
