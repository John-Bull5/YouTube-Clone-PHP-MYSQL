<?php

class CommentSection
{
    private $con;
    private $user;
    private $video;

    public function __construct($con, User $user, Video $video)
    {
        $this->con = $con;
        $this->user = $user;
        $this->video = $video;
    }

    public function create()
    {
        return $this->createCommentSection();
    }

    public function createCommentSection()
    {
        $commentNum = $this->video->getCommentsNumber();

        $postedBy = $this->user->getUsername();

        $videoId = $this->video->getId();

        $profileButton = ButtonProvider::createProfileButton($this->con,$postedBy);

        $commentAction = "postComment(this,\"$postedBy\",$videoId,null,\"comments\")";

        $commentButton = ButtonProvider::createButton('COMMENT','postComment btn btn-primary',null,$commentAction);

        $comments = $this->video->comments();
        
        return "
        <div class='commentSection'>
            <div class='header'>
                <span class='commentsCounts'>
                 $commentNum Comments 
                </span>
                <div class='commentForm form-group'>
                    $profileButton
                    <textarea name='' class='commentBody' placeholder='Add a public comment'></textarea>
                    $commentButton
                </div>
            </div>
            <div class='comments row'>
                $comments
            </div>
        </div>";
    }
}