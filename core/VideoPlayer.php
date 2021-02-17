<?php 

class VideoPlayer
{
    private $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function create($filePath = false)
    {
        
        if ($filePath) {
            $filePath = $this->video->getFilePath();
            return "<video src='" . $filePath . "' controls width='720px' height=400px'></video>";
        }else {
            return "Unable to fetch video";
        }
        
    }
}