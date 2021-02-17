<?php 

require_once('../includes/config.php');
require_once('../core/User.php');
require_once('../core/Comment.php');

if (isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId'])) 
{
    $postedBy = $_POST['postedBy'];
    $videoId = $_POST['videoId'];
    $replyTo = $_POST['responseTo'];
    $body = $_POST['commentText'];

    $statement = $con->prepare("INSERT INTO comments (postedBy,videoId,responseTo,body) VALUES(:postedBy,:videoId,:responseTo,:body)");

    $statement->bindParam(":postedBy",$postedBy);
    $statement->bindParam(":videoId",$videoId);
    $statement->bindParam(":responseTo",$replyTo);
    $statement->bindParam(":body",$body);

    $statement->execute();

    $commentId = $con->lastInsertId();
    $user = new User($con,$_SESSION['user']);

    // Comment class

    $comment = new Comment($con,$commentId,$user,$videoId);
    echo $comment->create();
}