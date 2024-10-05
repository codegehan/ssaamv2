<?php include 'header.php'; ?>
<style>
.form-step {display: none;}
.form-step.active {display: block;}
.form-container {position: relative;}
.form-step {position: absolute;top: 0;left: 0;width: 100%;}
.result{background-color: green;color:#fff;padding:20px;}
.row{display:flex;}
#reader{width: 400px;}
@media (max-width: 768px) {#reader{width: 300px !important;}}
</style>
<div class="container mt-5">
    <div class="form-container">
        <!-- Step 1 -->
        <div class="form-step active shadow" id="form1">
            <div class="p-4">
                <h4 class="pb-3">Required Information</h4>
                <form id="form1">
                    <div class="form-group text-start mb-3">
                        <label for="attendanceType" class="label-input">ATTENDANCE TYPE <span class="text-danger">*</span></label>
                        <select id="attendanceType" name="attendanceType" class="form-control mb-2" required>
                            <option value=""></option>
                            <option value="1">AM - IN</option>
                            <option value="2">AM - OUT</option>
                            <option value="3">PM - IN</option>
                            <option value="4">PM - OUT</option>
                            <option value="5">NIGHT - IN</option>
                            <option value="6">NIGHT - OUT</option>
                        </select>
                    </div>
                    <div class="form-group text-start mb-3">
                        <label for="activityName" class="label-input">ACTIVITY NAME <span class="text-danger">*</span></label>
                        <select id="activityName" name="activityName" class="form-control mb-2" required>
                            <option value=""></option>
                            <?php OptionMaker::DefaultPopulate("selectactivity",$d["abbrv"],"activitycode","description");?>
                        </select>
                    </div>
                    <div class="text-center mb-2 text-danger"><span id="message"></span></div>
                    <button type="button" class="btn btn-primary w-100" onclick="nextStep()">Proceed</button>
                </form>
            </div>
        </div>
        <!-- Step 2 -->
        <div class="form-step shadow" id="form2">
            <div class="p-4">
                <h4 class="pb-3">Scan Student</h4>
                <form id="form2">
                    <div class="row">
                        <div class="col">
                            <div id="reader"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="padding:30px;">
                            <div id="result"></div>
                        </div>
                    </div>
                </form>
                <button type="button" id="startScan" onclick="startScanning()" class="btn btn-sm btn-primary bg-gradient">Start Scanning</button>
	            <button type="button" id="stopScan" hidden onclick="stopScanning()" class="btn btn-sm btn-danger bg-gradient">Stop Scanning</button>
            </div>
        </div>
    </div>
</div>
<!-- <script src="../lib/html5-qrcode.min.js"></script> -->
<script src="../lib/html5-qrcode.min.v2.js"></script>
<script>
    
    function nextStep() {
        var attendanceType = document.getElementById('attendanceType').value;
        var activityName = document.getElementById('activityName').value;
        var errorMessage = document.getElementById('message');

        if(attendanceType === "" || activityName === "") {
            errorMessage.innerText = "Please select required fields.";
            return;
        }

        document.querySelector('.form-step.active').classList.remove('active');
        document.getElementById('form2').classList.add('active');
    }


    let html5QrCode;
    var startScanBtn = document.getElementById('startScan');
    var stopScanBtn = document.getElementById('stopScan');
    function onScanSuccess(qrCode, decodedResult) {
        // Handle the scanned code as you like
        stopScanning();
        // resultContainer.innerText += `QR Code Result: ${decodedText}<br>`;
        try {
            $.post('../lib/security.php', { data: qrCode, type: "decrypt" }, function(ress) {
                Tools.ExecuteSql('checkstudent', JSON.stringify({"studentid": ress.decrypted})).then(function(response) {
                    if (response.code === 1) {
                        console.error(response)
                        console.error(response.data)
                        if (confirm("Are you " + response.data.fname + " " + response.data.mname + " " + response.data.lname + "?")) {
                            // The user clicked "OK" (interpreted as "Yes")
                            var attendanceType = document.getElementById('attendanceType').value;
                            var activityName = document.getElementById('activityName').value;

                            const jsonStringData = JSON.stringify({
                                "studentid": response.data.student_id,
                                "atttype": attendanceType,
                                "activity": activityName
                            });
                            Tools.ExecuteSql('recordattendance', jsonStringData).then(function(attResponse) {
                                alert(attResponse.code === 1 ? "Success: " + attResponse.data : "Error: " + attResponse.data);
                                // isProcessing = false;
                            }).catch(function(attError) {
                                console.error('Error:', attError);
                                // isProcessing = false;
                            });
                        } else {
                            console.log("User chose No");
                            // isProcessing = false;
                        }
                    } else {
                        alert(response.data);
                        console.log("Error =", response.data);
                        // isProcessing = false;
                    }
                }).catch(function(error) {
                    console.error('Error:', error);
                    // isProcessing = false;
                });
            });
        } catch (err) {
            console.log(err);
            isProcessing = false;
        }
        startScanning();
    }

    function onScanError(errorMessage) {
        // Handle scan error
        console.warn(`QR Code scan error: ${errorMessage}`);
    }

    function startScanning(){
        startScanBtn.hidden = true;
        stopScanBtn.hidden = false;
        // Ensure the script is loaded and then create the scanner instance
        if (typeof Html5Qrcode !== "undefined") {
            html5QrCode = new Html5Qrcode("reader");
            // Start scanning the camera
            html5QrCode.start(
                { facingMode: "environment" }, // Use rear camera
                {
                    fps: 10,    // Scans per second
                    qrbox: 250  // Scanning box size (can be adjusted)
                },
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error(`Unable to start scanning, error: ${err}`);
            });
        } else {
            console.error("Html5Qrcode library is not loaded correctly.");
        }
    }

    function stopScanning(){
        startScanBtn.hidden = false;
        stopScanBtn.hidden = true;
        if (html5QrCode) {
            html5QrCode.stop();
            document.getElementById('reader').innerHTML = '';
        }
    }


    // let isProcessing = false;

    // function onScanSuccess(qrCode) {
    //     if (isProcessing) return;

    //     isProcessing = true;

    //     try {
    //         $.post('../lib/security.php', { data: qrCode, type: "decrypt" }, function(ress) {
    //             Tools.ExecuteSql('checkstudent', JSON.stringify({"studentid": ress.decrypted})).then(function(response) {
    //                 if (response.code === 1) {
    //                     if (confirm("Are you " + response.data.fname.toUpperCase() + " " + response.data.mname.toUpperCase() + " " + response.data.lname.toUpperCase() + "?")) {
    //                         // The user clicked "OK" (interpreted as "Yes")
    //                         var attendanceType = document.getElementById('attendanceType').value;
    //                         var activityName = document.getElementById('activityName').value;

    //                         const jsonStringData = JSON.stringify({
    //                             "studentid": response.data.student_id,
    //                             "atttype": attendanceType,
    //                             "activity": activityName
    //                         });
    //                         Tools.ExecuteSql('recordattendance', jsonStringData).then(function(attResponse) {
    //                             alert(attResponse.code === 1 ? "Success: " + attResponse.data : "Error: " + attResponse.data);
    //                             isProcessing = false;
    //                         }).catch(function(attError) {
    //                             console.error('Error:', attError);
    //                             isProcessing = false;
    //                         });
    //                     } else {
    //                         console.log("User chose No");
    //                         isProcessing = false;
    //                     }
    //                 } else {
    //                     alert(response.data);
    //                     console.log("Error =", response.data);
    //                     isProcessing = false;
    //                 }
    //             }).catch(function(error) {
    //                 console.error('Error:', error);
    //                 isProcessing = false;
    //             });
    //         });
    //     } catch (err) {
    //         console.log(err);
    //         isProcessing = false;
    //     }
    // }

    // function onScanError(errorMessage) { }

    // var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 150 });
    // html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>


<?php include("footer.php")?>