<?php 

class User
{
    private $con;
    private $sqlData;
    private $username;

    public function __construct(PDO $con,$username)
    {
        $this->con = $con;

        $statement = $this->con->prepare("SELECT * FROM users WHERE username=:username");

        $statement->bindParam(":username",$username);

        $statement->execute();

        $this->sqlData = $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsername()
    {
        return $this->sqlData['username'];
    }
    public function getEmail()
    {
        return $this->sqlData['email'];
    }
    public function getSignUpDate()
    {
        return $this->sqlData['signUpDate'];
    }
    public function getProfilePic()
    {
        return $this->sqlData['profilePic'];
    }

    public function getFullName()
    {
        return $this->sqlData['firstName'] . " " . $this->sqlData['lastName'];
    }

    public static function isUserLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    public function isSubcribedTo($userTo)
    {
        $username = $this->getUsername();
        $statement = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");

        $statement->bindParam(":userTo",$userTo);
        $statement->bindParam(":userFrom",$username);

        $statement->execute();

        return $statement->rowCount() > 0;
    }
    public function getSubscribersCount()
    {
        $username = $this->getUsername();

        $statement = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");

        $statement->bindParam(":userTo",$username);

        $statement->execute();

        return $statement->rowCount();
    }
}