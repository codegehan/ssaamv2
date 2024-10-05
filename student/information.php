<?php 
include('header.php');
if(isset($_GET["sid"])){
    $studentid = $_GET["sid"];
    $decryptedId = Crypto::Decrypt($studentid);
    $session_info = json_decode($_SESSION["student_information"], true);
    $d = $session_info[0];
    if ($decryptedId !== $d["student_id"]){
        header('Location:../?message=Unrecognized action');
        exit();
    }
}
?>
<style> #loader-upload {position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 1000;background: rgba(255, 255, 255, 0.8);display: flex;justify-content: center;align-items: center;flex-direction:column;} #loader img {width: 100px;height: auto;margin-bottom:10px;} #loading-message span {font-size:14px;color:#000;}</style>
<div id="loader-upload" hidden><img src="../img/ccs.gif" alt="Loading..."><div id="loading-message"><span>Uploading image...</span></div></div>
<form id="student-information-form" enctype="multipart/form-data">       
<div style="max-height:475px;overflow:scroll;">
    <img src="../img/ccs.png" alt="CCS Logo" style="max-width:80px;">
    <h5 class="title-blue custom-shadow">Student Information Form</h5>
    <hr>
    <div class="mx-1">
        <div class="form-group text-start mb-1">
            <label for="studentid" class="label-input">STUDENT ID <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="studentid" name="studentid" maxlength="15" value="<?php if(isset($_SESSION["student_information"])) { echo $d["student_id"];}?>" required>
        </div>
        <div class="form-group text-start mb-1">
            <label for="firstname" class="label-input">FIRST NAME <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="firstname" name="firstname" maxlength="30" value="<?php if(isset($_SESSION["student_information"])) { echo $d["fname"];}?>" required>
        </div>
        <div class="form-group text-start mb-1">
            <label for="middlename" class="label-input">MIDDLE NAME</label>
            <input type="text" class="form-control" id="middlename" name="middlename" maxlength="30" value="<?php if(isset($_SESSION["student_information"])) { echo $d["mname"];}?>">
        </div>
        <div class="form-group text-start mb-1">
            <label for="lastname" class="label-input">LAST NAME <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="lastname" name="lastname" maxlength="30" value="<?php if(isset($_SESSION["student_information"])) { echo $d["lname"];}?>" required>
        </div>
        <div class="form-group text-start mb-1">
            <label for="suffix" class="label-input">SUFFIX</label>
            <select id="suffix" name="suffix" class="form-control mb-2" value="<?php if(isset($_SESSION["student_information"])) { echo $d["suffix"];}?>">
                <option value=""></option>
                <option value="">N/A</option>
                <option value="jr" <?php if(isset($_SESSION["student_information"]) && $d["suffix"] === "jr") echo 'selected'; ?>>JR</option>
                <option value="sr" <?php if(isset($_SESSION["student_information"]) && $d["suffix"] === "sr") echo 'selected'; ?>>SR</option>
                <option value="ii" <?php if(isset($_SESSION["student_information"]) && $d["suffix"] === "ii") echo 'selected'; ?>>II</option>
                <option value="iii" <?php if(isset($_SESSION["student_information"]) && $d["suffix"] === "iii") echo 'selected'; ?>>III</option>
                <option value="iv" <?php if(isset($_SESSION["student_information"]) && $d["suffix"] === "iv") echo 'selected'; ?>>IV</option>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="email" class="label-input">EMAIL <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" maxlength="30" value="<?php if(isset($_SESSION["student_information"])) { echo $d["email"];}?>" required>
        </div>
        <div class="form-group text-start mb-1">
            <label for="yearlevel" class="label-input">YEAR LEVEL <span class="text-danger">*</span></label>
            <select id="yearlevel" name="yearlevel" class="form-control mb-2" required>
                <option value=""></option>
                <option value="1st year" <?php if(isset($_SESSION["student_information"]) && $d["year_level"] === "1st year") echo 'selected'; ?>>1ST YEAR</option>
                <option value="2nd year" <?php if(isset($_SESSION["student_information"]) && $d["year_level"] === "2nd year") echo 'selected'; ?>>2ND YEAR</option>
                <option value="3rd year" <?php if(isset($_SESSION["student_information"]) && $d["year_level"] === "3rd year") echo 'selected'; ?>>3RD YEAR</option>
                <option value="4th year" <?php if(isset($_SESSION["student_information"]) && $d["year_level"] === "4th year") echo 'selected'; ?>>4TH YEAR</option>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="semester" class="label-input">SEMESTER <span class="text-danger">*</span></label>
            <select id="semester" name="semester" class="form-control mb-2" required>
                <option value=""></option>
                <option value="1st semester" <?php if(isset($_SESSION["student_information"]) && $d["semester"] === "1st semester") echo 'selected'; ?>>1ST SEMESTER</option>
                <option value="2nd semester" <?php if(isset($_SESSION["student_information"]) && $d["semester"] === "2nd semester") echo 'selected'; ?>>2ND SEMESTER</option>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="schoolyear" class="label-input">SCHOOL YEAR <span class="text-danger">*</span></label>
            <select id="schoolyear" name="schoolyear" class="form-control mb-2" required>
                <option value=""></option>
                <?php OptionMaker::SpecialPopulate("selectschoolyear", "getschoolyear", "schoolyear", "schoolyear", "schoolyear");?>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="course" class="label-input">COURSE <span class="text-danger">*</span></label>
            <select id="course" name="course" class="form-control mb-2" required>
                <option value=""></option>
                <?php OptionMaker::SpecialPopulate("selectprogramenroll", "1", "value", "description", "course");?>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="program" class="label-input">PROGRAM <span class="text-danger">*</span></label>
            <select id="program" name="program" class="form-control mb-2" required>
                <option value=""></option>
                <?php OptionMaker::SpecialPopulate("selectprogramenroll", "2", "value", "description", "program");?>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="major" class="label-input">MAJOR</label>
            <select id="major" name="major" class="form-control mb-2">
                <option value=""></option>
                <?php OptionMaker::SpecialPopulate("selectprogramenroll", "3", "value", "description", "major");?>
            </select>
        </div>
        <div class="form-group text-start mb-1">
            <label for="major" class="label-input">PROFILE <span class="text-danger">*</span></label>
            <input type="file" id="profileImage" class="form-control" accept=".jpg, .jpeg">
        </div>
    </div>
</div>
<div class="pt-2">
    <p id="message"></p>
    <button type="button" class="btn btn-primary w-100 bg-gradient" id="submit-information">Submit</button>
</div>
</form>

<!-- Terms and condition here -->
<div class="modal fade" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agreementModalLabel">Terms and Conditions</h5>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="pb-3">
        <input type="checkbox" id="agreement">
        <label for="major" class="label-input">Aggree</label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    $('#submit-information').click(function(){
        var jsonData = Tools.FormInputs('student-information-form');
        Tools.ExecuteSql('studentupdate', jsonData).then(function(response) {
            if (response.code === 1) {
                var studentid = $('#studentid').val();
                var fileInput = document.getElementById('profileImage');
                var file = fileInput.files[0];
                if (file) {
                    var formData = new FormData();
                    formData.append('studentid', studentid);
                    formData.append('profileImage', file);
                    document.getElementById('loader-upload').hidden = false;
                    $.ajax({
                        url: '../lib/fileupload.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            try {
                                //console.log('File upload successful:', response.status);
                                document.getElementById('loader-upload').hidden = true;
				alert("Message: " + response.status);
                                alert("Please login again");
                                window.location.href = "logout.php";
                            } catch (e) {
                                //console.error('Error parsing response:', e);
                                alert('Image upload failed. Please login now');
				window.location.href = "logout.php";
                            }
                        },
                        error: function(xhr, status, error) {
                            document.getElementById('loader-upload').hidden = true;
                            //console.error('File upload failed:', xhr.responseText);
                            //console.error('Status:', status);
                            //console.error('Error:', error);
                            alert('File upload failed. Please login now.');
                            window.location.href = "logout.php";
                        }
                    });
                } else {
                    document.getElementById('loader-upload').hidden = true;
                    alert(response);
                }
            } else {
                alert('Student update failed:', response.data);
            }
        }).catch(function(error) {
            alert('Error during student update:', error);
        });
    });
    $(document).ready(function() {
        var hasAgreed = Tools.getCookie('hasAgreed');
        if (!hasAgreed) { $('#agreementModal').modal('show'); }
        $('#agreementModal').on('hidden.bs.modal', function (e) {
        if (!$('#agreement').is(':checked')) { window.location.href = '../index.php'; } 
        else { Tools.setCookie('hasAgreed', 'true', 1); }
        });
    });
</script>
<?php include('footer.php');?>
