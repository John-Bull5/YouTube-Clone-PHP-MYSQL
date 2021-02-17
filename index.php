<?php require_once("./vendor/autoload.php") ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('./core/VideoDetailsForm.php'); ?>

<?php
if (isset($_SESSION['user'])) {
    echo "Welcome " . $userObj->getEmail();
}
?>
<?php include_once('includes/footer.php'); ?>