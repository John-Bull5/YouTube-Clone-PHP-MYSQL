<?php 

require_once("ButtonProvider.php");

class CommentControls
{
    private $con;
    private $user;
    private $comment;

    public function __construct(PDO $con, User $user,Comment $comment)
    {
        $this->con = $con;
        $this->user = $user;
        $this->comment = $comment;
    }

    public function create()
    {
       $likeButton = $this->createLikeButton();
       $dislikeButton = $this->createDisLikeButton();
       $likeCount = $this->createLikesCount();
       $replyButton = $this->createReplyButton();
       $replySection = $this->createReplySection(); 

       return "
       <div class='controls'>
        $likeButton
        $dislikeButton
       </div>
       ";
    }

    private function createLikeButton()
    {
        $class = 'likeBtn';
        $icon = 'thumbs-up';
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();
        $action = "likeComment($commentId,this,$videoId)";

        if ($this->comment->wasLikedBy()) 
        {
            $icon = 'thumbs-up thumbs_active';
        }

        return ButtonProvider::createButton("", $class, $icon, $action);
    }
    
    private function createDisLikeButton()
    {
        $class = 'dislikeBtn';
        $icon = 'thumbs-down';
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();
        $action = "dislikeComment($commentId,this,$videoId)";

        if ($this->comment->wasDislikedBy()) 
        {
            $icon = 'thumbs-down thumbs_active';
        }
        return ButtonProvider::createButton("", $class, $icon, $action);
    }

    private function createLikesCount()
    {
        $text = $this->comment->getLikes();
        if ($text == 0) $text = "";
        return "<span class='likesCounts'>$text</span>";
    }

    private function createReplyButton()
    {
        $text = "REPLY";
        $action = "toggleReply(this)";
        return ButtonProvider::createButton($text,null,null,$action);
    }

    private function createReplySection()
    {
        # code...
    }


}
