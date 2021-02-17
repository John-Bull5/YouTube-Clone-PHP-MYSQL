<?php
require_once('../includes/config.php');
require_once('../core/Comment.php');
require_once('../core/User.php');

$videoId = $_POST['videoId'];

$commentId = $_POST['commentId'];

$username = $_SESSION['user'];

$user = new User($con, $username);

$comment = new Comment($con, $commentId, $user, $videoId);

echo $comment->like();
