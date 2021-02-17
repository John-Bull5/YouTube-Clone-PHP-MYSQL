<?php 
include_once("config.php"); 
include_once("./core/User.php");

$user = isset($_SESSION["user"]) ? $_SESSION['user'] : "";

$userObj = new User($con,$user);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="/assets/jquery-3.5.1.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/commonAction.js"></script>
    <script src="/assets/js/userAction.js"></script>
    <title>Video Tube</title>
</head>

<body>
    <div id="pageContainer">
        <!-- head section -->
        <div id="mastHeadContainer">
            <button class="navShowHide">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
            <a href="./../index.php" class="logoContainer">
                <i class="fab fa-youtube"> <strong class="text"> VideoTube</strong></i>
            </a>
            <div class="searchBarContainer">
                <form action="search.php" method="get">
                    <input type="text" name="term" class="searchBar" placeholder="Search...">
                    <button class="searchButton">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="rightIcons">
                <a class="upload" href="upload.php">
                    <i class="fa fa-upload"></i>
                </a>
                <a class="upload" href="#">
                    <i class="fa fa-user-circle"></i>
                </a>
            </div>
        </div>

        <!-- side nav container -->
        <div id="sideNavContainer"></div>


        <!-- main section -->
        <div id="mainSectionContainer">
            <div id="mainContentContainer">