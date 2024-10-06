<?php include("header.php");?>
<style>
@media (max-width: 767.98px) {
.table thead {display: none;}
.table tbody tr {display: block;margin-bottom: 15px;border: 1px solid #ddd;border-radius: 5px;}
.table td {display: block;padding: 2px 10px;border-bottom: 1px solid #ddd;position: relative;padding-left: 50%;}
.table td::before {content: attr(data-label);position: absolute;left: 10px;font-weight: bold;text-align: left;}
.table td:last-child {border-bottom: 0;}}
</style>
<div class="container mt-5">
    <div class="form-container">
        <div class="form-step active shadow" id="form1">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-primary bg-gradient btn-sm me-2" data-toggle="modal" data-target="#newAccountModal">New Account</button>
                    <input type="text" class="form-control w-50 py-1" placeholder="Search" id="accountSearchKey" oninput="searchAccount()">
                </div>
                <hr>
                <h4>Activity Lists</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Id</th>
                            <th>Fullname</th>
                            <th>College</th>
                            <th>Level</th>
                            <th>Position</th>
                            <th>Validated</th>
                            <th>Setting</th>
                        </tr>
                    </thead>
                    <tbody id="accountListBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newAccountModal" tabindex="-1" role="dialog" aria-labelledby="newAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAccountModalLabel">Acccount Information</h5>
      </div>
      <form id="accountForm">
        <div class="modal-body">
            <div class="form-group text-start mb-1">
                <label class="label-input">STUDENT ID</label>
                <input type="text" class="form-control text-uppercase" id="studentid" name="studentid" maxLength="15" required>
            </div>
            <div class="form-group text-start mb-1">
                <label class="label-input">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="password" maxLength="10" required>
            </div>
            <div class="form-group text-start mb-1">
                <label class="label-input">LEVEL</label>
                <select id="level" name="level" class="form-control mb-2">
                    <option value=""></option>
                    <?php OptionMaker::DefaultPopulate("selectaccountlevel", "getaccountlevel", "level_description", "level_description");?>
                </select>
            </div>
            <div class="form-group text-start mb-1">
                <label class="label-input">POSITION</label>
                <select id="position" name="position" class="form-control mb-2">
                    <option value=""></option>
                    <?php OptionMaker::DefaultPopulate("selectposition", "getposition", "position_description", "position_description");?>
                </select>
            </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" id="saveAccount" class="btn btn-primary btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
Tools.SubmitRequest('accountupdate','accountForm','saveAccount', 'loadAccountLists("")');
$('#newAccountModal').on('hide.bs.modal', function (e) { $('#accountForm')[0].reset(); });
function loadAccountList(data){
    var jsonData = JSON.stringify({"key": data});
    Tools.ExecuteSql('getaccountlist', jsonData).then(function(response) {
        if (response.code === 1) {
            let responseData;
             try {
                responseData = JSON.parse(response.data);
            } catch (e) {
                console.error('Failed to parse JSON');
                responseData = []; // Fallback to an empty array
            }
            
            // Ensure responseData is an array
            if (!Array.isArray(responseData)) {
                console.error('Response data is not an array');
                responseData = []; // Fallback to an empty array
            }

            let accountLists = '';
            let validated = '';
            let classMessage = '';
            if (responseData.length === 0) {
                // If no activities are found
                accountLists = '<tr><td colspan="7">No account found</td></tr>';
            } else {
                // Build the activity list
                responseData.forEach(i => {
                    validated = i.validated === 1 ? "Yes" : "No";
                    classMessage = i.validated === 1 ? "text-success" : "text-danger";
                    accountLists += `
                    <tr>
                        <td data-label="Student Id"><span class="text-uppercase">${i.studentid}</span></td>
                        <td data-label="Fullname"><span class="text-uppercase">${i.fullname}</span></td>
                        <td data-label="College"><span class="text-uppercase">${i.college}</span></td>
                        <td data-label="Level"><span class="text-uppercase">${i.level}</span></td>
                        <td data-label="Position"><span class="text-uppercase">${i.position}</span></td>
                        <td data-label="Validated"><span class="${classMessage}">${validated}</span></td>
                        <td data-label="Actions">
                            <button id="btnEditFor_${i.studentid}" data-toggle="modal" data-target="#newAccountModal" onclick="EditAccount('${i.studentid}','${i.level}','${i.position}')" class="btn btn-sm btn-success py-0">Edit</button>
                        </td>
                    </tr>
                    `;
                });
            }
            $('#accountListBody').html(accountLists);
        } else {
            console.log(response.data)
        }
    }).catch(function(error) { console.error('Error:', error); });
}
$(document).ready(function() { 
    loadAccountList(""); 
});
function EditAccount(studentid,level,position){
    $('#studentid').val(studentid);
    $('#level').val(level.toUpperCase());
    $('#position').val(position.toUpperCase());
}
function searchAccount(){
    let searchKey = document.getElementById('accountSearchKey').value;
    loadAccountList(searchKey);
}
</script>

<?php include("footer.php");?>