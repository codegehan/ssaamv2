<?php
require '../vendor/autoload.php';
require_once('jwthandler.php');
require_once('compress.php');
header('Content-Type: application/json');
session_start();
$response = ['status' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['profileImage']) && isset($_POST["studentid"])) {
            $studentid = $_POST["studentid"];
            $file = $_FILES['profileImage'];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = $file['type'];
                
                if (in_array($fileType, $allowedTypes)) {
                    $uploadDir = '../img/pictures/';
                    $uploadDirMin = '../img/pictures-min/';
                    $safeFileName = basename($studentid) . '.jpg';
                    $uploadFile = $uploadDir . $safeFileName;
                    $uploadFileMin = $uploadDirMin . $safeFileName;
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); } 
                    if (!is_dir($uploadDirMin)) { mkdir($uploadDirMin, 0755, true); }
                    $fileExists = file_exists($uploadFile);
                    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                        $compressedImage = Image::Compress($uploadFile, 50, 150, 150);
                        file_put_contents($uploadFileMin, $compressedImage);
                        $status = 'Image updated!';
                        $response['status'] = $status;
                    } else {
                        $response['status'] = 'Failed to move uploaded file.';
                        logError('Failed to move uploaded file: ' . print_r($file, true));
                    }
                } else {
                    $response['status'] = 'Invalid file type.';
                    logError('Invalid file type: ' . $fileType);
                }
            } else {
                $response['status'] = 'File upload error: ' . $file['error'];
                logError('File upload error: ' . $file['error']);
            }
        } else {
            $response['status'] = 'File or student ID not set.';
            logError('File or student ID not set.');
        }
    } else {
        $response['status'] = 'Invalid request method.';
        logError('Invalid request method.');
    }
} catch (Exception $e) {
    $response['status'] = 'Error: ' . $e->getMessage();
    logError('Exception: ' . $e->getMessage());
}

echo json_encode($response);
?>
