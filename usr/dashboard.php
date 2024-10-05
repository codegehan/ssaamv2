<?php include 'header.php'; ?>
<style>
.main-container     {display: grid;grid-template-rows: auto 1fr auto;grid-template-columns: 25% 75%;gap: 5px;}
.side-container     {grid-column: 1 / 2;grid-row: 1 / 4;color: white;padding: 16px;}
.middle-container   {grid-column: 2 / 3;grid-row: 1 / 4;color: white;padding: 16px;}
.container-bg       {background-color: #E4C580;}
th, .span-bold      {font-weight: bold;}
@media (max-width: 768px) {
.main-container     {grid-template-columns: 1fr;grid-template-rows: auto auto auto;}
.side-container     {grid-column: 1 / 2;grid-row: 1;}
.middle-container   {grid-column: 1 / 2;grid-row: 2;}}
</style>
<div class="main-container"">
    <div class="side-container my-3 shadow bg-light text-dark">
        <div class="text-center" id="side-container-content"></div>
    </div>
    <div class="middle-container mx-1 text-dark">

        <div class="row shadow bg-light mb-2">
            <div class="col mt-4">
                <form class="pb-3">
                    <div class="d-flex text-end">
                        <input type="text" class="form-control me-2 w-50" id="studentid_search" placeholder="Input student id here...">
                        <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div id="search-student-information"></div>
        </div>

        <div class="row shadow container-bg overflow-auto" style="height:30vh;">
            <div class="col">
                <div class="mt-3">
                    <h4>Activity Lists</h4>
                </div>
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <tr>
                                <td>Student Fest</td>
                                <td>Date</td>
                                <td>Active</td>
                            </tr>
                        </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#searchBtn').click(function(){
        var studentid = $("#studentid_search").val();
        var jsonData = JSON.stringify({"studentid": studentid});
        Tools.ExecuteSql('getstudent_byid_with_yearlevels', jsonData).then(function(response) {
            if (response.code === 1) {
                let previousLevelRows = '';

                if (response.data[0].previous_level && response.data[0].previous_level.length > 0) {
                response.data[0].previous_level.forEach(level => {
                    previousLevelRows += `
                    <tr>
                        <td>${level.school_year || 'N/A'}</td>
                        <td class="text-uppercase">${level.program || 'N/A'}</td>
                        <td class="text-uppercase">${level.year_level || 'N/A'}</td>
                        <td class="text-uppercase">${level.semester || 'N/A'}</td>
                    </tr>
                    `;
                });
            } else {
                previousLevelRows = `
                    <tr>
                        <td colspan="4" class="text-center">No previous levels available</td>
                    </tr>
                `;
            }

                document.getElementById('search-student-information').innerHTML = `
                <hr>
                <div class="mt-3">
                    <h4>Student Information</h4>
                </div>
                <div class="row">
                    <div class="col d-flex">
                        <img src="../img/pictures/${response.data[0].student_id}.jpg" alt="Photo not available" style="max-width:100px;max-height:100px;border:2px solid grey;margin-right:1.5rem;">
                        <div class="my-1">
                            <h6 class="text-uppercase"><span class="span-bold">Student Id:</span> ${response.data[0].student_id}</h6>
                            <h6 class="text-uppercase"><span class="span-bold">Name:</span> ${response.data[0].fname} ${response.data[0].mname} ${response.data[0].lname}</h6>
                            <h6 class="text-uppercase"><span class="span-bold">Year:</span> ${response.data[0].year_level}</h6>
                            <h6 class="text-uppercase"><span class="span-bold">College:</span> ${response.data[0].course}</h6>
                            <h6 class="text-uppercase"><span class="span-bold">Program:</span> ${response.data[0].program}</h6>
                            <h6 class="text-uppercase"><span class="span-bold">Major:</span> ${response.data[0].major}</h6>
                        </div>
                    </div>
                    <div class="col">
                        <div style="max-height:200px;display:block;overflow-y:auto;">
                            <table class="table table-info bg-gradient">
                                <thead>
                                    <tr>
                                        <th>SY</th>
                                        <th>College</th>
                                        <th>Year Level</th>
                                        <th>Semester</th>
                                    </tr>
                                </thead>
                                <tbody>${previousLevelRows}</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col d-flex p-relative">
                        <div class="mb-2">
                            <button class="btn btn-danger">View Sanction</button>
                        </div>
                    </div>
                </div>`;
            } else {
                $('#search-student-information').html("<strong class='text-danger'>Error searching student</strong>");
                setTimeout(() => { $('#search-student-information').html(''); }, 3000);
            }
        }).catch(function(error) { console.error('Error:', error); });
    });


    $(document).ready(function() {
        getTotal();
    });


    function getTotal() {
        var jsonData = JSON.stringify({"key": "gettotal"});
        Tools.ExecuteSql('gettotalcount', jsonData).then(function(response) {
            if (response.code === 1) {
                let collegeList = '';
                response.data.forEach(total => {
                    collegeList += `
                    <div class="mt-3">
                        <div class="row">
                            <div class="col">
                                <span class="text-uppercase">${total.course}</span>
                            </div>
                            <div class="col">
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </div>
                            <div class="col">
                                <span class="text-uppercase">${total.total_course_registered}</span>
                            </div>
                        </div>
                    </div>
                    `;
                });

                document.getElementById('side-container-content').innerHTML = `
                <h5>Total student registered:</h5>
                <div>
                    <h2 class="text-danger">${response.total_registered}</h2>
                </div>
                <hr>
                ${collegeList}
                `;
            } else { console.log("Error loading college lists.")}// Posible no response data from database here
        }).catch(function(error) { console.error('Error:', error); });
    }
    // updating the view each 30 seconds
    setInterval(function() {
        getTotal();
    }, 30000);
</script>
<?php include 'footer.php'; ?>