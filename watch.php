<?php include_once('includes/header.php'); ?>
<?php include_once('core/VideoInfo.php'); ?>
<?php include_once('core/commentSection.php'); ?>
<?php include_once('core/Video.php'); ?>
<?php include_once('core/VideoPlayer.php'); ?>

<?php
if (isset($_GET['id'])) {
    $video = new Video($con, $_GET['id'], $userObj);

    $video->incrementView();
} else {
    echo "no id passed";
}
?>
<script src="./assets/js/videoPlayerAction.js"></script>
<script src="./assets/js/commentAction.js"></script>

<div class="row">
    <div class="col-12">
        <?php
        $videoPlayer = new VideoPlayer($video);
        echo $videoPlayer->create(true);

        $videoInfo = new VideoInfo($con,$userObj,$video);
        echo $videoInfo->create();
        
        $comments = new CommentSection($con,$userObj,$video);
        echo $comments->create();
        ?>
    </div>
    
</div>
<?php include_once('includes/footer.php'); ?>