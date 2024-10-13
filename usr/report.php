<?php include 'header.php'; ?>
<style>.fullname{min-width:250px;word-wrap:break-word;}.customFont{font-size:0.8rem;}</style>
<style> #generateReportIndicator {position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 1000;background: rgba(255, 255, 255, 0.8);display: flex;justify-content: center;align-items: center;flex-direction:column;} #loader img {width: 100px;height: auto;margin-bottom:10px;} #loading-message span {font-size:14px;color:#000;}</style>
<div id="generateReportIndicator" hidden><img src="../img/ccs.gif" alt="Loading..."><div id="loading-message"><span>Generating report. Please wait...</span></div></div>
<div class="controller-container">
    <button class="btn btn-lg btn-success w-100 mb-3 py-3" data-toggle="modal" data-target="#reportModal">Export to Excel <i style="font-style:italic;">(Officer)</i></button>
    <button class="btn btn-lg btn-secondary w-100 py-3" data-toggle="modal" data-target="#reportModalStudent">Export to Excel <i style="font-style:italic;">(Student)</i></button>
</div>
</div>
<!-- OFficer Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="reportModalLabel">Officer Attendance</h5>
        </div>
        <div class="modal-body customFont">
            <form id="generateOfficerReportForm">
                <div class="row">

                    <?php if(strtoupper($d["level"]) == "ADMINISTRATOR" || strtoupper($d["level"]) == "PRESIDENT" || strtoupper($d["level"]) == "VICE PRESIDENT") { ?>
                    <div class="col">
                        <label for="formGroupExampleInput">Activity</label>
                        <select name="activity" id="activityName" class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("getactivities_all", "data", "code", "description");?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Course</label>
                        <select name="course" id="ownerAbbrv"  class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectprogramenroll", 1, "value", "description");?>
                        </select>
                    </div>
                    <?php } else { ?>
                    <div class="col">
                        <label for="formGroupExampleInput">Activity</label>
                        <select name="activity" id="activityName" class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectactivity",$d["abbrv"],"activitycode","description");?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Course</label>
                        <input type="text" class="form-control" id="ownerAbbrv" placeholder="Course" value="<?=strtoupper($d["abbrv"])?>" disabled>
                        <input type="text" class="form-control" name="course" placeholder="Course" value="<?=strtoupper($d["course_code"])?>" hidden>
                    </div>
                    <?php }?>  

                </div>
            </form>
            <div class="mt-3 table-responsive">
                <table id="reportTable" class="table table-bordered w-100" style="max-height:450px;overflow:scroll;table-layout:auto;font-size:0.6rem;"></table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="generateBtn" class="btn btn-sm btn-primary">Generate</button>
            <button type="button" id="exportBtn" class="btn btn-sm btn-success">Export</button>
        </div>
        </div>
    </div>
</div>
<!-- Student Modal -->
<div class="modal fade" id="reportModalStudent" tabindex="-1" role="dialog" aria-labelledby="reportModalStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="reportModalStudentLabel">Student Attendance</h5>
        </div>
        <div class="modal-body customFont">
            <form id="generateStudentReportForm">
                <div class="row">
                    <?php if(strtoupper($d["level"]) == "ADMINISTRATOR" || strtoupper($d["level"]) == "PRESIDENT" || strtoupper($d["level"]) == "VICE PRESIDENT") { ?>
                    <div class="col">
                        <label for="formGroupExampleInput">Activity</label>
                        <select name="activity" id="activityNameStudent" class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("getactivities_all", "data", "code", "description");?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="formGroupExampleInput">Year Level</label>
                        <select name="yearlevel" id="yearlevelName" class="form-control customFont">
                            <option value=""></option>
                            <option value="1st year">1st Year</option>
                            <option value="2nd year">2nd Year</option>
                            <option value="3rd year">3rd Year</option>
                            <option value="4th year">4th Year</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Course</label>
                        <select name="course" id="ownerAbbrvStudent"  class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectprogramenroll", 1, "value", "description");?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="formGroupExampleInput">Program</label>
                        <select name="program" id="programName" class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectprogramenroll", 2, "value", "description");?>
                        </select>
                    </div>
                    <?php } else { ?>

                    <div class="col">
                        <label for="formGroupExampleInput">Activity</label>
                        <select name="activity" id="activityNameStudent" class="form-control customFont">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectactivity",$d["abbrv"],"activitycode","description");?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="formGroupExampleInput">Year Level</label>
                        <select name="yearlevel" id="yearlevelName" class="form-control customFont">
                            <option value=""></option>
                            <option value="1st year">1st Year</option>
                            <option value="2nd year">2nd Year</option>
                            <option value="3rd year">3rd Year</option>
                            <option value="4th year">4th Year</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Course</label>
                        <input type="text" class="form-control" id="ownerAbbrvStudent" placeholder="Course" value="<?=strtoupper($d["abbrv"])?>" disabled>
                        <input type="text" class="form-control" name="course" placeholder="Course" value="<?=strtoupper($d["course_code"])?>" hidden>
                    </div>
                    <div class="col">
                        <label for="formGroupExampleInput">Program</label>
                        <select name="program" id="programName" class="form-control">
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectprogramenroll", 2, "value", "description");?>
                        </select>
                    </div>
                    <?php }?>  
                </div>
            </form>
            <div class="mt-3 table-responsive customFont">
                <table id="reportTableStudent" class="table table-bordered w-100 display" style="max-height:450px;overflow:scroll;table-layout:auto;font-size:0.6rem;"></table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="generateBtnStudent" class="btn btn-sm btn-primary">Generate</button>
            <button type="button" id="exportBtnStudent" class="btn btn-sm btn-success">Export</button>
        </div>
        </div>
    </div>
    </div>
<script>

let fullDataSet = [];
// Officer Functions
$('#generateBtn').click(function(){
    document.getElementById('generateReportIndicator').hidden = false;
    // Clear previous DataTable if it exists
    if ($.fn.DataTable.isDataTable('#reportTable')) {
        $('#reportTable').DataTable().clear().destroy();  // Clear and destroy the existing DataTable
    }
    document.getElementById('reportTable').innerHTML = ''; // Clear the inner HTML
    fullDataSet = []; // Reset the fullDataSet
    var jsonData = Tools.FormInputs('generateOfficerReportForm');
    Tools.SqlTableAdapter('generatereport_officer', jsonData).then(function(response) {
        Tools.PopulateTable('reportTable', response); // Populate the table with new data
        fullDataSet = response; // Update the fullDataSet with the new data
        $('#reportTable').DataTable(); // Initialize the DataTable
        Tools.addClassToColumnByHeader('reportTable', 'fullname', 'fullname'); // Add class to specific column
    }).catch(function(error) {
        console.error('Error:', error);
    });
    document.getElementById('generateReportIndicator').hidden = true;
});
$('#exportBtn').click(function(){
    var activitySelect = document.getElementById('activityName');
    var owner = document.getElementById('ownerAbbrv');
    var selectedText = activitySelect.options[activitySelect.selectedIndex].textContent;
    if (owner.tagName.toLowerCase() === 'select') { 
        var selectedOwner = owner.options[owner.selectedIndex].textContent;
        owner = selectedOwner;
    } else { owner = owner.value; }
    Tools.createHiddenTableFromData(fullDataSet);
    Tools.ExportTableToExcel('tempExportTable', selectedText, selectedText, owner);
});
$('#reportModal').on('hide.bs.modal', function (e) { $('#reportTable').html(''); $('#generateOfficerReportForm')[0].reset();});


// Student function
$('#generateBtnStudent').click(function(){
    document.getElementById('generateReportIndicator').hidden = false;
    // Clear previous DataTable if it exists
    if ($.fn.DataTable.isDataTable('#reportTableStudent')) {
        $('#reportTableStudent').DataTable().clear().destroy();  // Clear and destroy the existing DataTable
    }
    document.getElementById('reportTableStudent').innerHTML = ''; // Clear the inner HTML
    fullDataSet = []; // Reset the fullDataSet
    var jsonData = Tools.FormInputs('generateStudentReportForm');
    Tools.SqlTableAdapter('generatereport_student', jsonData).then(function(response) {
        Tools.PopulateTable('reportTableStudent', response); // Populate the table with new data
        fullDataSet = response; // Update the fullDataSet with the new data
        $('#reportTableStudent').DataTable(); // Initialize the DataTable
        Tools.addClassToColumnByHeader('reportTableStudent', 'fullname', 'fullname'); // Add class to specific column
    }).catch(function(error) {
        console.error('Error:', error);
    });
    document.getElementById('generateReportIndicator').hidden = true;
});
$('#exportBtnStudent').click(function(){
    var activitySelect = document.getElementById('activityNameStudent');
    var owner = document.getElementById('ownerAbbrvStudent');
    var selectedText = activitySelect.options[activitySelect.selectedIndex].textContent;
    if (owner.tagName.toLowerCase() === 'select') { 
        var selectedOwner = owner.options[owner.selectedIndex].textContent;
        owner = selectedOwner;
    } else { owner = owner.value; }
    Tools.createHiddenTableFromData(fullDataSet);
    Tools.ExportTableToExcel('tempExportTable', selectedText, selectedText, owner);
});
$('#reportModalStudent').on('hide.bs.modal', function (e) { $('#reportTableStudent').html(''); $('#generateStudentReportForm')[0].reset();});
</script>

<?php include 'footer.php'; ?>
