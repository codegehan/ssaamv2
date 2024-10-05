<?php 
include('header.php');
if(!isset($_GET["sid"]) || !isset($_SESSION["student_information"]) || !isset($_SESSION["token"])){
  session_unset();
  session_destroy();
  header('location:../');
  exit();
} else {
  if(!JwtHandler::gatewayInit($_SESSION["token"])) {
      session_unset();
      session_destroy();
      header('location: ../?message=Token expired. Please login again');
      exit();
  }
}
if(isset($_GET["sid"])){
  $sid = $_GET["sid"];
  $decryptedId = Crypto::Decrypt($sid);
  $studentInfo = json_decode($_SESSION["student_information"], true);
  $d = $studentInfo[0];
  $qrCodeMaker = new QRCodeGenerator($sid);
  $base64QrCode = $qrCodeMaker->generateQRCode();  
}
if(isset($_POST["logoutBtn"]))  { session_destroy(); header("Location: ../");}
?>
<style>.bold{font-weight:bold;color:#011;}</style>
<div class="text-start" style="max-width: 280px;">
    <div class="text-center">
      <img class="mb-1" src="../img/pictures/<?=$decryptedId?>.jpg" alt="Student Profile" style="width:150px;border:2px solid #DFDFDF;border-radius:10px;">
    </div>
    <hr>
    <h6 style="font-size:20px;font-weight:bold;color:purple;" class="text-uppercase"><?=$d["fname"]?> <?=$d["mname"]?> <?=$d["lname"]?></h6>
    <h6 class="text-uppercase"><span class="bold">Year Level: </span><?=$d["year_level"]?></h6>
    <h6 class="text-uppercase"><span class="bold">Course: </span> <?=$d["course"]?></h6>
    <h6 class="text-uppercase"><span class="bold">Program: </span> <?=$d["program"]?></h6>
    <h6 class="text-uppercase"><span class="bold">Major: </span> <?php echo ($d["major"] !== null && $d["major"] !== "") ? $d["major"] : "N/A"; ?></h6>
    <h6 class="text-uppercase"><span class="bold">Email: </span><?=$d["email"]?></h6>
    <div class="pt-2 d-flex justify-content align-items-center">
        <button type="button" class="btn btn-sm btn-primary bg-gradient me-1" data-toggle="modal" data-target="#qrCodeModal">QR Code</button>
        <a href="information.php?sid=<?=$sid?>">
          <button type="button" class="btn btn-sm btn-secondary .bg-gradient me-1">Update</button>
        </a>
        <form action="" method="post">
          <button type="submit" name="logoutBtn" class="btn btn-sm btn-danger bg-gradient">Logout</button>
        </form>
    </div>
</div>
<div class="modal fade text-center" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img src="data:image/png;base64,<?=$base64QrCode?>" class="my-4 border" alt="QrCode" style="max-width: 200px;">
      </div>
    </div>
  </div>
</div>
<?php include('footer.php');?>