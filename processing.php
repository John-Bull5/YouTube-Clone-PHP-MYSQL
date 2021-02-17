<?php 
include_once('includes/header.php');
include_once('./core/VideoUploadData.php');
include_once('./core/VideoProcessor.php');

$fileInput = $_FILES['fileInput'];
$titleInput = $_POST['titleInput'];
$descriptionInput = $_POST['descriptionInput'];
$privacyInput = $_POST['privacyInput'];
$categoryInput = $_POST['categoryInput'];
$uploadedBy = $userObj->getUsername();
// check for form submisiion
if (!isset($_POST['uploadBtn'])) {
    echo "No form data is sent";
}
// make the file upload data

$videoUploadData = new VideoUploadData(
    $fileInput,
    $titleInput,
    $descriptionInput,
    $privacyInput,
    $categoryInput,
    $uploadedBy
);

$videoProcessor = new VideoProcessor($con);
$wasSuccessful = $videoProcessor->upload($videoUploadData);

//processing the videoto mp4 format

//check for successfully upload ofthe video to database
if ($wasSuccessful) {
    echo "Video Uploaded Successfully";
}