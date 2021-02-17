<?php
include_once("./includes/config.php");
include_once("./core/Constants.php");
include_once("./core/Account.php");
include_once("./core/FormSanitizer.php");

//process the sign up form data

$account = new Account($con);

if (isset($_POST['submitBtn'])) {
    $username = FormSanitizer::santizeFormUserName($_POST['username']);
    $password = FormSanitizer::santizeFormPassword($_POST['password']);
    //validation

    $success = $account->login($username, $password);

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
    <title>Sign in</title>
</head>

<body>
    <div class="signInContainer container">
        <div class="column">
            <div class="header">
                <i class="fab fa-youtube"> <strong class="text"> VideoTube</strong></i>
                <h3>Sign In</h3>
                <span>to continue to VideoTube</span>
            </div>
            <div class="loginForm">
                <form action="signIn.php" method="post">
                <?php echo $account->getError(Constants::$incorrectDetail);?>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" value="<?php oldValue('username'); ?>" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" value="<?php oldValue('password'); ?>" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="submitBtn" class="btn btn-primary mt-2 mb-3">
                </form>

            </div>
            <a class="signInMessage" href="signUp.php">
                Don't have an account? Sign up here.
            </a>
        </div>
    </div>
</body>

</html>