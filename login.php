<?php
    include('conn/conn.php');
    require 'vendor/autoload.php';
    include('lib/crypto.php');
    include('lib/jwthandler.php');
    $spname = "";
    if(!isset($_POST["studentid"]) || !isset($_POST["password"])){
        header('location: index.php');
    } else {
        $id = $_POST["studentid"];
        $pass = $_POST["password"];
        $logintype = $_POST["logintype"];
        if(strtoupper($logintype) == "OFFICER") { $spname = "officer_login"; } 
        elseif(strtoupper($logintype) == "NON-OFFICER") { $spname = "student_login"; }
        $jsonData = json_encode(array(
            "studentid" => $id,
            "password" => $pass
        ));

        try{
            $stmt = $pdo->prepare("call $spname(:jsonData)");
            $stmt->bindParam(":jsonData",$jsonData);
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $decodedResult = json_decode($result['Result'], true);
                $code = $decodedResult['code'];
                $decodedData = $decodedResult['data'];
                if ($code == 1) {
                    session_start();
                    $encryptedId = Crypto::Encrypt($id);
                    $jwtHandler = new JwtHandler();
                    $payload = ['id' => $encryptedId, 'timestamp' => time()];
                    $token = $jwtHandler->generateToken($payload);
                    $_SESSION["student_information"] = json_encode($decodedData);
                    $_SESSION["token"] = $token;
                    if(strtoupper($logintype) == "OFFICER") { header("Location:usr/dashboard.php?sid={$encryptedId}"); } 
                    elseif(strtoupper($logintype) == "NON-OFFICER") { header("Location:student/dashboard.php?sid={$encryptedId}"); }
                    exit();
                } elseif($code == 0) {
                    header("Location:./?message={$decodedData}");
                    exit();
                }
            } else {
                echo json_encode(array("code" => "0", "data" => "Error executing query"));
            }
        } catch (PDOException $e) { echo json_encode(array("code" => "0", "data" => "Database error: " . $e->getMessage())); }
    }
?>
