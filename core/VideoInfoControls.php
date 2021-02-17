<?php
require_once("core/ButtonProvider.php");

class VideoInfoControls
{
    private $user;
    private $video;

    public function __construct(User $user, Video $video)
    {
        $this->user = $user;
        $this->video = $video;
    }

    public function create()
    {
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDisLikeButton();

        return "
       <div class='controls'>
        $likeButton
        $dislikeButton
       </div>
       ";
    }

    private function createLikeButton()
    {
        $text = $this->video->getLikes();
        $class = 'likeBtn';
        $icon = 'thumbs-up';
        $videoId = $this->video->getId();
        $action = "likeVideo(this,$videoId)";

        if ($this->video->wasLikedBy()) {
            $icon = 'thumbs-up thumbs_active';
        }

        return ButtonProvider::createButton($text, $class, $icon, $action);
    }

    private function createDisLikeButton()
    {
        $text = $this->video->getDislikes();
        $class = 'dislikeBtn';
        $icon = 'thumbs-down';
        $videoId = $this->video->getId();
        $action = "dislikeVideo(this,$videoId)";

        if ($this->video->wasDislikedBy()) {
            $icon = 'thumbs-down thumbs_active';
        }
        return ButtonProvider::createButton($text, $class, $icon, $action);
    }
}
