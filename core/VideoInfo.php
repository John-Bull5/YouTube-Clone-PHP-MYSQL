<?php
require_once("core/VideoInfoControls.php");

class VideoInfo
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
        return $this->createPrimarySection() . $this->createSecondarySection();
    }

    private function createPrimarySection()
    {
        $title = $this->video->getTitle();

        $views = $this->video->getView();

        $videoControls = new VideoInfoControls($this->user, $this->video);

        $controls = $videoControls->create();

        return "
        <div class='videoInfo'>
            <h1>$title<h1/>
            <div class='bottomSection'>
                <span class='viewCounts'>
                    $views views
                </span>
                $controls
            </div>
        </div>
        ";
    }

    private function createSecondarySection()
    {
        $description = $this->video->getDescription();
        $uploadDate = $this->video->getUploadDate();
        $uploadedBy = $this->video->getUploadedBy();
        $profileButton = ButtonProvider::createProfileButton($this->con, $uploadedBy);

        //check whether the logged in user is the owner of the video

        if ($uploadedBy == $this->user->getUsername()) {
            $actionButton = ButtonProvider::createEditVideoButton($this->video->getId());
        } else {
            $userTo = new User($this->con, $uploadedBy);
            $actionButton = ButtonProvider::createSubscriberButton($this->con, $userTo, $this->user);
        }
        return "
        <div class='secondaryInfo'>
            <div class='topRow'>
                $profileButton
            
                <div class='uploadInfo'>
                    <div class='owner'>
                        <span>
                            <a href='profile.php?username=$uploadedBy'>$uploadedBy</a>
                        </span>
                    </div>
                
                    <div class='date'>
                        published on $uploadDate
                    </div>
                </div>
                    $actionButton
            </div>
                <div class='description'>
                    $description
                </div>
        </div>
        ";
    }
}
