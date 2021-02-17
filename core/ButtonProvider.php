<?php 

class ButtonProvider
{
    public static $signInFunction = 'signInFunction()';

    public static function createLink($action)
    {
        return User::isUserLoggedIn() ? $action : ButtonProvider::$signInFunction;
    }

    public static function createButton($text,$class,$icon,$action)
    {
        $icon = ($icon == null) ? "" : "<i class='fa fa-$icon'></i>";
        $action = ButtonProvider::createLink($action);

        return "<button class='$class' onclick='$action'>
            $icon
            <span class='text'>$text</span>
        </button>";
    }

    public static function createHyperLinkButton($text, $class, $icon, $href)
    {
        $icon = ($icon == null) ? "" : "<i class='fa fa-$icon'></i>";
        
        return "<a href='$href'>
        <button class='$class' >
            $icon
            <span class='text'>$text</span>
        </button>
        </a>";
    }

    public static function createProfileButton(PDO $con,$username)
    {
        $user = new User($con,$username);

        $profilePic = $user->getProfilePic();

        $link = "profile.php?username=$username";
       
       return "
         <a href='$link'>
             <img src='$profilePic' class='profilePicture'>
         </a>
       ";
    }

    public static function createEditVideoButton($vidoeId)
    {
        $href = "editVideo.php?id=$vidoeId";

        $editVideoButton = self::createHyperLinkButton("EDIT VIDEO","edit button",null,$href);

        return "<div class='editVideoBtnContainer'>
            $editVideoButton
        </div>";
    }

    public static function createSubscriberButton(PDO $con,User $userToObj,User $userLoggedInObj)
    {
        //grab the user that have the video
        $userTo = $userToObj->getUsername();
        //grab the currently logged in user
        $userLoggedIn = $userLoggedInObj->getUsername();

        //check if the currently logged in user is subscribed to

        $isSubscribedTo = $userLoggedInObj->isSubcribedTo($userTo);
        
        $buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";

        $buttonText .= " " . $userToObj->getSubscribersCount();

        $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";

        $action = "subscribe(\"$userTo\",\"$userLoggedIn\",this)";

        $button = self::createButton($buttonText,$buttonClass,null,$action);

        return "<div class='subscribeBtnContainer'>$button</div>";
    }
    
}