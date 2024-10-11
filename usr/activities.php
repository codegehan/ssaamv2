<?php include 'header.php'; ?>
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
        <!-- Step 1 -->
        <div class="form-step active shadow" id="form1">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-primary bg-gradient btn-sm me-2" data-toggle="modal" data-target="#newActivityModal">New Activity</button>
                    <input type="text" class="form-control w-50 py-1" placeholder="Search" id="searchKey" oninput="searchActivity()">
                </div>
                <hr>
                <h4>Activity Lists</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Owner</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activityListBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Add Activity Modal -->
<div class="modal fade" id="newActivityModal" tabindex="-1" role="dialog" aria-labelledby="newActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newActivityModalLabel">Activity Information</h5>
      </div>
      <form id="activityForm">
        <div class="modal-body">
            <input type="text" name="code" id="code" hidden>
            <input type="text" name="addedby" value="<?=$decryptedId?>" id="addedby" hidden>
            <div class="form-group text-start mb-1">
                <label class="label-input">DESCRIPTION</label>
                <input type="text" class="form-control text-uppercase" id="description" name="description">
            </div>
            <?php 
                if(strtoupper($d["level"]) == "ADMINISTRATOR") { ?>
                <div class="form-group text-start mb-1">
                    <label class="label-input">OWNER</label>
                    <select id="owner" name="owner" class="form-control mb-2" onchange="checkValue()" required>
                        <option value="all">ALL</option>
                        <?php OptionMaker::DefaultPopulate("selectprogramenroll", 1, "abbrv", "description");?>
                    </select>
                </div>
            <?php } else { ?>
                <div class="form-group text-start mb-1">
                    <label class="label-input">OWNER</label>
                    <input type="text" class="form-control text-uppercase" id="ownerDisplay" value="<?=$d["course"]?>" disabled>
                    <input type="text" class="form-control" name="owner" id="owner" value="<?=$d["course"]?>" hidden>
                </div>
            <?php }?>
            <div class="form-group text-start mb-1">
                <label class="label-input">START DATE</label>
                <input type="date" class="form-control" name="startDate" id="startDate">
            </div>
            <div class="form-group text-start mb-1">
                <label class="label-input">END DATE</label>
                <input type="date" class="form-control" name="endDate" id="endDate">
            </div>
            <div class="form-group text-start mb-1 d-flex align-items-center mt-3">
                <label class="label-input me-3">STATUS:</label>
                <div class="custom-control custom-radio custom-control-inline me-3">
                    <input type="radio" id="openActivity" name="activityAvailability" class="custom-control-input" value="1" checked>
                    <label class="custom-control-label" for="openActivity" id="isOpen">Open</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="closeActivity" name="activityAvailability" class="custom-control-input" value="0">
                    <label class="custom-control-label" for="closeActivity" id="isClosed">Close</label>
                </div>
            </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" id="saveActivity" class="btn btn-primary btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
$('#saveActivity').click(function(){
    var jsonData = Tools.FormInputs('activityForm');
    var code = $('#code').val();
    var newJsonData = JSON.parse(jsonData);

    if (code !== null && code !== '') {
        newJsonData.code = code;
    }
    jsonData = JSON.stringify(newJsonData);
    console.log(jsonData);
    Tools.ExecuteSql('newactivity', jsonData).then(function(response) {
        if (response.code === 1) {
            alert(response.data);
            $('#activityForm')[0].reset();
            loadActivities(""); 
        } else {
            alert(response.data);
        }
    }).catch(function(error) { console.error('Error:', error); });
});

$('#newActivityModal').on('hide.bs.modal', function (e) {
    $('#activityForm')[0].reset();
});

function loadActivities(data){
    var jsonData = JSON.stringify({"data": data});
    Tools.ExecuteSql('getactivities', jsonData).then(function(response) {
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

            let activityLists = '';
            let isOpen = '';
            let classMessage = '';
            if (responseData.length === 0) {
                // If no activities are found
                activityLists = '<tr><td colspan="6">No activity found</td></tr>';
            } else {
                // Build the activity list
                responseData.forEach(i => {
                    isOpen = i.isopen === 1 ? "Yes" : "No";
                    classMessage = i.isopen === 1 ? "text-success" : "text-danger";

                    activityLists += `
                    <tr>
                        <td data-label="Description"><span class="text-uppercase">${i.description}</span></td>
                        <td data-label="Owner"><span class="text-uppercase">${i.owner}</span></td>
                        <td data-label="Start">${i.startdate}</td>
                        <td data-label="End">${i.enddate}</td>
                        <td data-label="Availability"><span class="${classMessage}">${isOpen}</span></td>
                        <td data-label="Actions">
                            <button id="btnEditFor_${i.code}" data-toggle="modal" data-target="#newActivityModal" onclick="editActivityBtn('${i.code}','${i.description}','${i.owner}','${i.startdate}','${i.enddate}','${isOpen}')" class="btn btn-sm btn-success py-0">Edit</button>
                        </td>
                    </tr>
                    `;
                });
            }
            $('#activityListBody').html(activityLists);
        } else {
            console.log(response.data)
        }
    }).catch(function(error) { console.error('Error:', error); });
}

$(document).ready(function() { 
    loadActivities(""); 
});

function searchActivity(){
    let searchKey = document.getElementById('searchKey').value;
    loadActivities(searchKey);
}
function editActivityBtn(code,desc,owner,start,end,isopen){
    $('#code').val(code);
    $('#description').val(desc);
    $('#owner').val(owner);
    $('#startDate').val(start);
    $('#endDate').val(end);
    if (isopen.toUpperCase() === "YES") {
        $('#openActivity').prop('checked', true);
    } else {
        $('#closeActivity').prop('checked', true);
    }
}

function checkValue(){
    console.log($('#owner').val())
}
</script>

<?php include 'footer.php'; ?>