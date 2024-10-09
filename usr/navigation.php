<?php 
$studentInfo = json_decode($_SESSION["student_information"], true);
$d = $studentInfo[0];

// if ($decryptedId !== $d["student_id"]){
//   header('Location:../?message=Unrecognized action');
//   exit();
// }
$access_dasboard='hidden';
$access_report='hidden';
$access_receipt='hidden';
$access_account='hidden';
$access_activites='hidden';
$access_scan='hidden';

$access = $d["level"];

if(strtoupper($access) == "ADMINISTRATOR") 
{
  $access_dasboard='';
  $access_report='';
  $access_receipt='';
  $access_account='';
  $access_activites='';
  $access_scan='';
} 
elseif(strtoupper($access) == "SG OFFICERS")
{
  $access_dasboard='';
  $access_report='';
  $access_receipt='';
  $access_activites='';
}
else
{
  $access_scan='';
  $access_dasboard='';
}

?>
<style>
nav{box-shadow: 0px 4px 10px 0px rgba(0,0,0,0.75);}
.pallete1 {background-color: #080F5B !important;}
.nav-link{color: #ffffff !important;}
.nav-link:hover{text-decoration: underline !important;color: #E4C580 !important;}
.dropdown-menu .dropdown-submenu {position: relative;}
.dropdown-menu .dropdown-submenu .dropdown-menu {top: 0;left: 100%;margin-left: .1rem;margin-right: .1rem;}
/* .navbar-toggler {border-color: #fff;} */
.navbar-toggler-icon {background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 30 30%27%3E%3Cpath stroke=%27%23ffffff%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-miterlimit=%2710%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E');}
.navbar-brand {display: flex;align-items: center;}
</style>
<nav class="navbar navbar-expand-lg navbar-light pallete1">
  <div class="container-fluid">
    <div class="navbar-brand">
      <img src="../img/ccs.png" alt="College of Computing Studies Logo" style="width: 40px;">
    </div>
    <button class="navbar-toggler" style="border:none;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <!-- <span class="navbar-toggler-icon"></span> -->
        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#FFFFFF"><path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/></svg>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="dashboard.php?sid=<?php echo $_GET["sid"];?>" <?=$access_dasboard?>>Dashboard</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="report.php?sid=<?php echo $_GET["sid"];?>" <?=$access_report?>>Report</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="tooltip" title="On development process" id="navbarDropdown" role="button" aria-expanded="false" <?=$access_receipt?>>
            Receipts
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">New receipt</a></li>
            <li><a class="dropdown-item" href="#">Receipt Definition</a></li>
            <li><a class="dropdown-item" href="#">Audit</a></li>
          </ul>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="activities.php?sid=<?php echo $_GET["sid"];?>" <?=$access_activites?>>Activities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="accounts.php?sid=<?php echo $_GET["sid"];?>" <?=$access_account?>>Accounts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="scan.php?sid=<?php echo $_GET["sid"];?>" <?=$access_scan?>>Scan Attendance</a>
        </li>
      </ul>
      <!-- for drop down navigation -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-uppercase py-0 px-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?=$d["fname"]?> <?=substr($d["mname"],0,1)?>. <?=$d["lname"]?><!-- Login user details --> 
          </a>
          <div class="text-muted py-0"><span style="font-style:italic;font-size:12px;color:#c1c1c1;margin-top:-5px;" class="text-uppercase"><?=$d["position"]?></span></div>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
              <a class="dropdown-item" href="logout.php?pos=<?=$d["position"]?>">Logout</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
      dropdownSubmenus.forEach(submenu => {
        submenu.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          const openSubmenus = document.querySelectorAll('.dropdown-submenu.show');
          openSubmenus.forEach(otherSubmenu => {
              if (otherSubmenu !== this) {
                  otherSubmenu.classList.remove('show');
                  const otherSubMenu = otherSubmenu.querySelector('.dropdown-menu');
                  if (otherSubMenu) {
                      otherSubMenu.classList.remove('show');
                  }
              }
          });
          this.classList.toggle('show');
          const subMenu = this.querySelector('.dropdown-menu');
          subMenu.classList.toggle('show');
        });
      });
      document.addEventListener('click', function (e) {
        const openSubmenus = document.querySelectorAll('.dropdown-submenu.show');
        openSubmenus.forEach(submenu => {
          submenu.classList.remove('show');
          const subMenu = submenu.querySelector('.dropdown-menu');
          subMenu.classList.remove('show');
        });
      });
    });
  </script>
