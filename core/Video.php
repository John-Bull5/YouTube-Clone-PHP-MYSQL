<?php
require_once('Comment.php');
class Video
{
    private $con;
    private $sqlData;
    private $user;
    private $username;
    private $videoId;

    public function __construct(PDO $con, $input, User $user)
    {
        $this->con = $con;
        $this->user = $user;

        if (is_array($input)) {
            $this->sqlData = $input;
        } else {
            $statement = $this->con->prepare("SELECT * FROM videos WHERE id=:id");

            $statement->bindParam(":id", $input);

            $statement->execute();

            $this->sqlData = $statement->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getId()
    {
        return $this->sqlData['id'];
    }

    public function getUploadedBy()
    {
        return $this->sqlData['uploadedBy'];
    }

    public function getTitle()
    {
        return $this->sqlData['title'];
    }

    public function getDescription()
    {
        return $this->sqlData['description'];
    }

    public function getPrivacy()
    {
        return $this->sqlData['privacy'];
    }

    public function getFilePath()
    {
        return $this->sqlData['filePath'];
    }

    public function getCategory()
    {
        return $this->sqlData['category'];
    }

    public function getUploadDate()
    {
        $date = $this->sqlData['uploadDate'];
        return date('M j, Y', strtotime($date));
    }

    public function getView()
    {
        return $this->sqlData['views'];
    }

    public function getDuration()
    {
        return $this->sqlData['duration'];
    }

    public function incrementView()
    {
        $statement = $this->con->prepare("UPDATE videos SET views=views + 1 WHERE id=:id");

        $statement->bindParam(":id", $this->videoId);

        $this->videoId = $this->getId();

        $statement->execute();

        $this->sqlData['views'] = $this->sqlData['views'] + 1;
    }

    public function getLikes()
    {
        $statement = $this->con->prepare("SELECT count(*) as count FROM likes WHERE videoId=:videoId");

        $statement->bindParam(":videoId", $this->videoId);

        $this->videoId = $this->getId();

        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }

    public function getDislikes()
    {
        $statement = $this->con->prepare("SELECT count(*) as count FROM dislikes WHERE videoId=:videoId");

        $statement->bindParam(":videoId", $this->videoId);

        $this->videoId = $this->getId();

        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }

    public function like()
    {
        $videoId = $this->getId();
        $username = $this->user->getUsername();

        if ($this->wasLikedBy()) {
            $statement = $this->con->prepare("DELETE FROM likes WHERE videoId=:videoId AND username=:username");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $result = array(
                'likes' => -1,
                'dislikes' => 0,
            );
            return json_encode($result);
        } else {
            //delete the dislike before liking the video
            $statement = $this->con->prepare("DELETE FROM dislikes WHERE videoId=:videoId AND username=:username");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $count = $statement->rowCount();

            $statement = $this->con->prepare("INSERT INTO likes (username,videoId) VALUES(:username,:videoId)");
            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $result = array(
                'likes' => 1,
                'dislikes' => 0 - $count,
            );
            return json_encode($result);
        }
    }

    public function dislike()
    {
        $videoId = $this->getId();
        $username = $this->user->getUsername();

        if ($this->wasDislikedBy()) {
            $statement = $this->con->prepare("DELETE FROM dislikes WHERE videoId=:videoId AND username=:username");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $result = array(
                'dislikes' => -1,
                'likes' => 0,
            );
            return json_encode($result);
        } else {
            //delete the dislike before liking the video
            $statement = $this->con->prepare("DELETE FROM likes WHERE videoId=:videoId AND username=:username");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $count = $statement->rowCount();

            $statement = $this->con->prepare("INSERT INTO dislikes (username,videoId) VALUES(:username,:videoId)");
            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);

            $statement->execute();

            $result = array(
                'dislikes' => 1,
                'likes' => 0 - $count,
            );
            return json_encode($result);
        }
    }

    public function wasLikedBy()
    {
        $videoId = $this->getId();
        $username = $this->user->getUsername();

        $statement = $this->con->prepare("SELECT * FROM likes WHERE videoId=:videoId AND username=:username");

        $statement->bindParam(":videoId", $videoId);
        $statement->bindParam(":username", $username);

        $statement->execute();

        return $statement->rowCount();
    }

    public function wasDislikedBy()
    {
        $videoId = $this->getId();
        $username = $this->user->getUsername();

        $statement = $this->con->prepare("SELECT * FROM dislikes WHERE videoId=:videoId AND username=:username");

        $statement->bindParam(":videoId", $videoId);
        $statement->bindParam(":username", $username);

        $statement->execute();

        return $statement->rowCount();
    }

    public function getCommentsNumber()
    {
        $statement = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
        $statement->bindParam(":videoId", $this->videoId);

        $statement->execute();

        return $statement->rowCount();
    }

    public function comments()
    {
        $profilePic = $this->user->getProfilePic();

        $html = "<div class='row ml-4'>";

        $statement = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");

        $statement->bindParam(":videoId", $this->videoId);
        $statement->execute();

        //return $statement->rowCount();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $postedBy = $row['postedBy'];
            $body = $row['body'];
            $datePosted = $row['datePosted'];
            
            $html .= 
            "<div class='col-1'>
                <img src='$profilePic' class='profilePicture mt-2'>    
            </div>
            <div class='col-11 mt-2'>
                <div class='user'>
                    $postedBy 
                    <span class='time'>$datePosted</span>
                </div>
                <div class='userComments'>$body</div>
            <div class='replies'></div>
            </div>
            ";
        }
        $html.= "</div>";



        return $html;
    }
}
