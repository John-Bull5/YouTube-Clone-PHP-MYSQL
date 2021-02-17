<?php

class VideoProcessor
{
    private $con;

    private $sizeLimit = 250000000;
    private $allowedType = array('mp4', '3gp', 'mov', 'mkv', 'avi', 'flv');
    private $ffmpegPath;
    private $ffprobePath;

    public function __construct(PDO $con)
    {
        $this->con = $con;
        $this->ffmpegPath = realpath("FFmpeg/bin/ffmpeg.exe");
        $this->ffprobePath = realpath("FFmpeg/bin/ffprobe.exe");
    }

    public function upload($videoUploadData)
    {
        //create a new folder for videos

        $targetDir = "uploads/videos/";

        //getting the video data

        $videoData = $videoUploadData->getVideoFile();

        // creating a temp path

        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);

        // replace all spaces with _

        $tempFilePath = str_replace("", "_", $tempFilePath);

        echo $tempFilePath;

        //validation for the video
        $isValidData = $this->processData($videoData, $tempFilePath);

        if (!$isValidData) {
            // it contains error
            return false;
        }
        //move video into folder

        if (move_uploaded_file($videoData['tmp_name'], $tempFilePath)) {
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo "Insert Query Failed";
                return false;
            }
            //convert video to mp4
            if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
                echo "Command failed";
                return false;
            }

            //delete the original video files

            if (!$this->deleteFile($tempFilePath)) {
                echo "Couldn't delete the video file";
                return false;
            }

            if (!$this->generateThumbnails($finalFilePath)) {
                echo "Upload failed";
                return false;
            }
            return true;
        }
    }

    private function processData($videoData, $tempFilePath)
    {
        $videoType = pathinfo($tempFilePath, PATHINFO_EXTENSION);

        if (!$this->isValidSize($videoData)) {
            echo "File is too large to upload";
            return false;
        } elseif (!$this->isValidType($videoType)) {
            echo "Invalid file type";
            return false;
        } elseif ($this->hasError($videoData)) {
            echo 'An error occured';
            return false;
        }
        return true;
    }

    private function isValidSize($videoData)
    {
        return $videoData["size"] <= $this->sizeLimit;
    }

    private function isValidType($videoType)
    {
        $videoType = strtolower($videoType);
        return in_array($videoType, $this->allowedType);
    }
     
    private function hasError($videoData)
    {
        return $videoData['error'] != 0;
    }

    private function insertVideoData($videoUploadData, $finalFilePath)
    {
        $title = $videoUploadData->getTitle();
        $description = $videoUploadData->getDescription();
        $category = $videoUploadData->getCategory();
        $privacy = $videoUploadData->getPrivacy();
        $uploadedBy = $videoUploadData->getUploadedBy();

        $statement = $this->con->prepare(
            "INSERT INTO videos (uploadedBy,title,description,privacy,filePath,category) VALUES(:uploadedBy,:title,:description,:privacy,:filePath,:category)"
        );
        $statement->bindParam(":uploadedBy", $uploadedBy);
        $statement->bindParam(":title", $title);
        $statement->bindParam(":description", $description);
        $statement->bindParam(":privacy", $privacy);
        $statement->bindParam(":filePath", $finalFilePath);
        $statement->bindParam(":category", $category);

        return $statement->execute();
    }

    private function convertVideoToMp4($tempFilePath, $finalFilePath)
    {
        // create a cmd statement
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

        $outputLog = array();

        //execute the command

        exec($cmd, $outputLog, $returnCode);

        //check for return code

        if ($returnCode != 0) {
            foreach ($outputLog as $line) {
                echo $line . "<br>";
                return false;
            }
        }

        return true;
    }

    private function deleteFile($FilePath)
    {
        $deleted = unlink($FilePath);
        if (!$deleted) {
            echo "Couldn't delete the file";
            return false;
        }
        return true;
    }

    private function generateThumbnails($finalFilePath)
    {
        # size of the thumbnail

        $thumbnailSize = '210*118';
        $thumbnailNum = 3;
        $pathToThumbnail = 'uploads/videos/thumbnails';
        $duration = $this->getVideoDuration($finalFilePath);

        $videoId = $this->con->lastInsertId();

        $this->updateDuration($duration, $videoId);

        for ($num = 1; $num <= 3; $num++) {
            $imageName = uniqid() . '.jpg';
            $interval = ($duration * 0.8) / $thumbnailNum * $num;

            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            //execute the command

            // create a cmd statement
            $cmd = "$this->ffmpegPath -i $finalFilePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

            $outputLog = array();

            //execute the command

            exec($cmd, $outputLog, $returnCode);

            //check for return code

            if ($returnCode != 0) {
                foreach ($outputLog as $line) {
                    echo $line . "<br>";
                }
            }
            $statement = $this->con->prepare(
                "INSERT INTO thumbnails(videoId,filePath,selected) VALUES(:videoId,:filePath,:selected)"
            );
           
            $statement->bindParam(":videoId", $videoId);
            $statement->bindParam(":filePath", $fullThumbnailPath);
            $statement->bindParam(":selected", $selected);

            $selected = $num == 1 ? 1 : 0;
            $success = $statement->execute();

            if (!$success) {
                echo "insert query failed";
                return false;
            }
        }
        return true;
    }

    private function getVideoDuration($FilePath)
    {
        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $FilePath");
    }

    private function updateDuration($duration, $videoId)
    {
        //convert the duration to int

        $hour = floor($duration / 3600);

        $minutes = floor(($duration - ($hour * 3600)) / 60);

        $seconds = floor($duration % 60);

        if ($hour < 1) {
            $hour = "";
        } else {
            $hour = $hour . ":";
        }
        if ($minutes < 10) {
            $minutes = "0" . $minutes . ":";
        } else {
            $minutes = $minutes . ":";
        }
        if ($seconds < 0) {
            $seconds = "0" . $seconds;
        } else {
            $seconds = $seconds;
        }
        //insert into database

        $duration = $hour . $minutes . $seconds;

        $statement = $this->con->prepare("UPDATE videos SET duration=:duration
       WHERE id=:id
       ");

        $statement->bindParam(":duration", $duration);
        $statement->bindParam(":id", $videoId);

        return $statement->execute();
    }
}
