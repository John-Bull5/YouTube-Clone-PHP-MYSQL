<?php
include_once("./includes/config.php");
include_once("./core/Constants.php");
include_once("./core/Account.php");
include_once("./core/FormSanitizer.php");

//process the sign up form data

$account = new Account($con);

if (isset($_POST['submitBtn'])) {
    $firstName = FormSanitizer::santizeFormString($_POST['firstName']);
    $lastName = FormSanitizer::santizeFormString($_POST['lastName']);
    $username = FormSanitizer::santizeFormUserName($_POST['username']);
    $email = FormSanitizer::santizeFormEmail($_POST['email']);
    $confirmEmail = FormSanitizer::santizeFormEmail($_POST['email2']);
    $password = FormSanitizer::santizeFormPassword($_POST['password']);
    $confirmPassword = FormSanitizer::santizeFormPassword($_POST['password2']);

    //validation

    $success = $account->register($firstName, $lastName, $username, $email, $confirmEmail, $password, $confirmPassword,$profilePic);

    if ($success) {
        $_SESSION["user"] = $username;
        header("Location: index.php");
    }
}

function oldValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/Font-awesome/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="/assets/jquery-3.5.1.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <title>Sign up</title>
</head>

<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <i class="fab fa-youtube"> <strong class="text"> VideoTube</strong></i>
                <h3>Sign Up</h3>
                <span>to continue to VideoTube</span>
            </div>
            <div class="loginForm">
                <form action="signUp.php" method="post">
                    <div class="row">
                        <?php echo $account->getError(Constants::$emptyField) ?>
                        <?php echo $account->getError(Constants::$firstNameLength) ?>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="firstName" value="<?php oldValue('firstName'); ?>" placeholder="First Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $account->getError(Constants::$emptyField) ?>
                        <?php echo $account->getError(Constants::$lastNameLength) ?>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="lastName" value="<?php oldValue('lastName'); ?>" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $account->getError(Constants::$emptyField) ?>
                        <?php echo $account->getError(Constants::$userNameLength) ?>
                        <?php echo $account->getError(Constants::$userNameExist) ?>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" class="form-control" name="username" value="<?php oldValue('username'); ?>" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $account->getError(Constants::$emptyField) ?>
                        <?php echo $account->getError(Constants::$emailMatch) ?>
                        <?php echo $account->getError(Constants::$invalidEmail) ?>
                        <?php echo $account->getError(Constants::$emailExist) ?>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" value="<?php oldValue('email'); ?>" placeholder="Email">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email2" placeholder="Confirm Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $account->getError(Constants::$emptyField) ?>
                        <?php echo $account->getError(Constants::$passwordMatch) ?>
                        <?php echo $account->getError(Constants::$invalidPassword) ?>
                        <?php echo $account->getError(Constants::$passwordLength) ?>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password2" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="submitBtn" class="btn btn-primary mt-2 mb-3">
                </form>
            </div>
            <a class="signInMessage" href="signIn.php">
                Already have an account? Sign in here.
            </a>
        </div>
    </div>
</body>

</html>