<?php

require_once('../includes/config.php');

if (isset($_POST['userTo']) && isset($_POST['userFrom'])) {
    $userTo = $_POST['userTo'];
    $userFrom =
        $_POST['userFrom'];

    $statement = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");

    $statement->bindParam(":userTo", $userTo);
    $statement->bindParam(":userFrom", $userFrom);
    $statement->execute();

    if ($statement->rowCount() == 0) {
        # subscribe the user 
        # insert query

        $statement = $con->prepare("INSERT INTO subscribers (userTo,userFrom) VALUES (:userTo,:userFrom)");

        $statement->bindParam(":userTo", $userTo);
        $statement->bindParam(":userFrom", $userFrom);
        $statement->execute();
    } else {
        # unsubscribe the user
        # delete query

        $statement = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");

        $statement->bindParam(":userTo", $userTo);
        $statement->bindParam(":userFrom", $userFrom);
        $statement->execute();
    }
    // display subscribers

    $statement = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo ");

    $statement->bindParam(":userTo", $userTo);

    $statement->execute();

    echo $statement->rowCount();
} else {
    echo 'value is not passed';
}
