<?php 
require 'vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/lib');
$dotenv->load();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <link rel="icon" type="image/png" href="img/ssaam-new.png">
    <title>SSAAM</title>
</head>
<body>
    <div class="d-block">
        <div class="text-center" id="header-context">
            <h3 class="title-blue custom-shadow mb-1" style="font-weight:bold;">Student School Activities Attendance Monitoring System</h3>
            <h4 class="title-purple mb-1" style="font-weight:bold;">College of Computing Studies</h4>
            <p class="title-blue mb-1">Jose Rizal Memorial State University</p>
            <p class="title-gold mb-0">Main Campus, Dapitan City</p>
        </div>
        <div class="container h-auto my-3" style="max-width:350px;">
            <div class="bg-light pt-3 text-center shadow border rounded">
                <img src="img/ssaam.png" alt="SSAAM Logo" class="img-fluid my-3" style="width:60%;">
                <form action="login.php" method="post">
                    <div class="p-3">
                        <select class="form-control mb-2" name="logintype">
                            <option value="non-officer">Student</option>
                            <option value="officer" selected>Admin</option>
                        </select>
                        <input type="text" name="studentid" class="form-control mb-2" maxlength="15" placeholder="Student Id" required>
                        <input type="password" name="password" class="form-control" maxlength="15" placeholder="Password" required>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary bg-gradient w-100">Login</button>
                            <p class="pt-3 pb-0">No account yet? <a href="student/information.php">Register</a></p>
                        </div>
                        <p class="text-danger"><?php if(isset($_GET["message"])){echo $_GET["message"];}?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

<footer class="fixed-bottom bg-light text-center py-2">
    <p class="mb-0">Powered by: Creatives Committee ~ v<?=$_ENV['VERSION']?></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- <script src="lib/transition.js"></script> -->
<style>
body{ opacity: 0; visibility: hidden; transition: opacity 0.3s ease-in; }
.fade-in{ opacity: 1; visibility: visible; }
.fade-out{ opacity: 0; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
document.querySelector('body').classList.add('fade-in');
});
$(document).ready(function () {
    $('form').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
        // Capture form data
        var formData = $(this).serialize();
        // Send the form data using AJAX
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                var jsonResult = JSON.parse(response);
                var code = jsonResult['code'];
                if (code === 0) {
                    $('.text-danger').text(jsonResult['message']);
                } else if (code === 1) {
                    document.querySelector('body').classList.add('fade-out');
                    var loginType = jsonResult['loginType'];
                    if (loginType.toUpperCase() === "OFFICER") { window.location.href = "usr/dashboard.php?sid=" + jsonResult['id']; } 
                    else if (loginType.toUpperCase() === "NON-OFFICER") { window.location.href = "student/dashboard.php?sid=" + jsonResult['id']; }
                }
            },
            error: function () {
                $('.text-danger').text(response);
            }
        });
    });
});
</script>
</body>
</html>