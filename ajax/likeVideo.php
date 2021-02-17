<?php
require_once('../includes/config.php');
require_once('../core/Video.php');
require_once('../core/User.php');

$videoId = $_POST['videoId'];

$username = $_SESSION['user'];

$user = new User($con, $username);

$video = new Video($con, $videoId, $user);

echo $video->like();
