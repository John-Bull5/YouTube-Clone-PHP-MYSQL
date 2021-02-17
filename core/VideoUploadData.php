<?php 

class VideoUploadData
{
    private $videoFile;
    private $title;
    private $description;
    private $privacy;
    private $category;
    private $uploadedBy;

    public function __construct($videoFile,$title,$description,$privacy,$category,$uploadedBy)
    {
        $this->videoFile = $videoFile;
        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploadedBy = $uploadedBy;
    }

    public function getVideoFile()
    {
        return $this->videoFile;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getPrivacy()
    {
        return $this->privacy;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }
}