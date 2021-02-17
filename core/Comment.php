<?php 
require_once('ButtonProvider.php');
require_once('CommentControl.php');

class Comment
{
    private $con;
    private $sqlData;
    private $user;
    private $videoId;

    public function __construct(PDO $con,$input,User $user, $videoId)
    {
        $this->con = $con;

        if (!is_array($input)) 
        {
            $statement = $this->con->prepare("SELECT * FROM comments WHERE id=:id");

            $statement->bindParam(':id',$input);

            $statement->execute();

            $input = $statement->fetch(PDO::FETCH_ASSOC);
        }

        $this->sqlData = $input;
        $this->user = $user;
        $this->videoId = $videoId;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

    public function getId()
    {
        return $this->sqlData['id'];
    }

    public function wasLikedBy()
    {
        $commentId = $this->getId();
        $username = $this->user->getUsername();

        $statement = $this->con->prepare("SELECT * FROM likes WHERE commentId=:commentId AND username=:username");

        $statement->bindParam(":commentId", $commentId);
        $statement->bindParam(":username", $username);

        $statement->execute();

        return $statement->rowCount();
    }

    public function wasDislikedBy()
    {
        $commentId = $this->getId();
        $username = $this->user->getUsername();

        $statement = $this->con->prepare("SELECT * FROM dislikes WHERE commentId=:commentId AND username=:username");

        $statement->bindParam(":commentId", $commentId);
        $statement->bindParam(":username", $username);

        $statement->execute();

        return $statement->rowCount();
    }

    public function create()
    {
        $body = $this->sqlData['body'];
        $postedBy = $this->sqlData['postedBy'];

        $profileButton = ButtonProvider::createProfileButton($this->con,$postedBy);

        $timeSpan = '';

        $commentControlObj = new CommentControls($this->con,$this->user,$this);

        $commentControl = $commentControlObj->create();

        return "
        <div class='itemContainer'>
            <div class='comment'>
                $profileButton
                <div class='mainContainer'>
                    <div class='commentHeader'>
                        <a href='profile.php?username=$postedBy'>
                            <span class='username>$postedBy</span>
                            <span class='timestamp'>$timeSpan</span>
                        </a>
                    </div>
                    <div class='body'>
                        $body
                    </div>
                </div>
            </div>
            $commentControl
        </div>";
    }

    public function getLikes()
    {
        // get number of likes - number of dislikes

        $statement = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM likes WHERE commentId=:commentId");

        $commentId = $this->sqlData['id'];

        $statement->bindParam(":commentId", $commentId);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $numLikes = $result['count'];

        // dislikes


        $statement = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM dislikes WHERE commentId=:commentId");

        $commentId = $this->sqlData['id'];

        $statement->bindParam(":commentId", $commentId);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $numDislikes = $result['count'];

        return $numLikes - $numDislikes;
    }

    public function like()
    {
        $commentId = $this->getId(); 

        $videoId = $this->videoId;

        $username = $this->user->getUsername();

        if ($this->wasLikedBy()) 
        {
            $statement = $this->con->prepare("DELETE FROM likes WHERE videoId=:videoId AND username=:username AND commentId=:commentId");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);
            $statement->bindParam(":commentId", $commentId);

            $statement->execute();

            $result = array(
                'likes' => -1,
                'dislikes' => 0,
            );
            return json_encode($result);
        } else {
            //delete the dislike before liking the comment
            $statement = $this->con->prepare("DELETE FROM dislikes WHERE videoId=:videoId AND username=:username AND commentId=:commentId");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);
            $statement->bindParam(":commentId", $commentId);

            $statement->execute();

            $count = $statement->rowCount();

            $statement = $this->con->prepare("INSERT INTO likes (commentId,username,videoId) VALUES(:commentId,:username,:videoId)");

            $statement->bindParam(":commentId", $commentId);
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
        $commentId = $this->getId();
        $videoId = $this->videoId;
        $username = $this->user->getUsername();

        if ($this->wasDislikedBy()) {
            $statement = $this->con->prepare("DELETE FROM dislikes WHERE videoId=:videoId AND username=:username AND commentId=:commentId");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);
            $statement->bindParam(":commentId", $commentId);

            $statement->execute();

            $result = array(
                'dislikes' => -1,
                'likes' => 0,
            );
            return json_encode($result);
        } else 
        {
            //delete the dislike before liking the video
            $statement = $this->con->prepare("DELETE FROM likes WHERE videoId=:videoId AND username=:username AND commentId=:commentId");

            $statement->bindParam(":username", $username);
            $statement->bindParam(":videoId", $videoId);
            $statement->bindParam(":commentId", $commentId);

            $statement->execute();

            $count = $statement->rowCount();

            $statement = $this->con->prepare("INSERT INTO dislikes (commentId,username,videoId) VALUES(:commentId,:username,:videoId)");

            $statement->bindParam(":commentId", $commentId);
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
}