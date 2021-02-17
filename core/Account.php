<?php

class Account
{
    private $con;

    private $error = [];

    public function __construct(PDO $con)
    {
        $this->con = $con;
    }

    public function register($firstname, $lastname, $username, $email, $confirmEmail, $password, $password2,$profilePic)
    {
        $this->validateFirstName($firstname);
        $this->validateLastName($lastname);
        $this->validateUserName($username);
        $this->validateEmails($email, $confirmEmail);
        $this->validatePasswords($password, $password2);

        if (empty($this->error)) {
            return $this->saveUser($firstname, $lastname,$username, $email, $password,$profilePic);
        } else {
            return false;
        }
    }

    public function login($username,$password)
    {
        $password = hash("sha512",$password);

        $statement = $this->con->prepare("SELECT * FROM users WHERE  username=:username AND password=:password");
        
        $statement->bindParam(":username",$username);
        $statement->bindParam(":password",$password);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            return true;
        } else {
            array_push($this->error,Constants::$incorrectDetail);
            return false;
        }
    }

    private function validateFirstName($firstname)
    {
        if (strlen($firstname) > 25 || strlen($firstname) < 2) {
            array_push($this->error, Constants::$firstNameLength);
            return;
        }
    }
    private function validateLastName($lastname)
    {
        if (strlen($lastname) > 25 || strlen($lastname) < 2) {
            array_push($this->error, Constants::$lastNameLength);
            return;
        }
    }
    private function validateUserName($username)
    {
        if (strlen($username) > 25 || strlen($username) < 5) {
            array_push($this->error, Constants::$userNameLength);
            return;
        }
        //check if username already exists

        $statement = $this->con->prepare("SELECT username FROM users WHERE username=:username");

        $statement->bindParam(":username", $username);
        $statement->execute();
        if ($statement->rowCount() != 0) {
            # then username exists
            array_push($this->error, Constants::$userNameExist);
        }
    }
    private function validateEmails($email, $confirmEmail)
    {
        if ($email != $confirmEmail) {
            array_push($this->error, Constants::$emailMatch);
            return;
        }
        //check if email is valid or not

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->error, Constants::$invalidEmail);
            return;
        }

        $statement = $this->con->prepare("SELECT email FROM users WHERE email=:email");

        $statement->bindParam(":email", $email);
        $statement->execute();

        if ($statement->rowCount() != 0) {
            # then username exists
            array_push($this->error, Constants::$emailExist);
        }
    }

    private function validatePasswords($password, $password2)
    {
        if ($password != $password2) {
            array_push($this->error, Constants::$passwordMatch);
            return;
        }
        //check if email is valid or not
        if (preg_match("/[^A-Za-z0-9]/", $password)) {
            array_push($this->error, Constants::$invalidPassword);
            return;
        }

        if (strlen($password) > 25 || strlen($password) < 5) {
            array_push($this->error, Constants::$passwordLength);
            return;
        }
    }

    public function getError($error)
    {
        if (in_array($error, $this->error)) {
            return "<div style='display:block'; class='invalid-feedback text-capitalize text-center'>
            $error
            </div>";
        }
    }

    private function saveUser($firstname, $lastname,$username, $email, $password)
    {
        $profilePic = "assets/images/profilePictures/me.jpg";

        $password = hash('sha512', $password);

        $statement = $this->con->prepare("INSERT INTO users (firstName,lastName,username,email,password,profilePic) VALUES(:firstName,:lastName,:username,:email,:password,:profilePic)");

        $statement->bindParam(":firstName", $firstname);
        $statement->bindParam(":lastName", $lastname);
        $statement->bindParam(":username", $username);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":profilePic", $profilePic);

        return $statement->execute();
    }
}
