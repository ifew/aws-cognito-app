<?php
require '../vendor/autoload.php';

use AWSCognitoApp\AWSCognitoWrapper;

$wrapper = new AWSCognitoWrapper();
$wrapper->initialize();

$error = '';
if(isset($_POST['action'])) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($_POST['action'] === 'register') {
        $email = $_POST['email'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $given_name = $_POST['given_name'] ?? '';
        $family_name = $_POST['family_name'] ?? '';
        $gender = $_POST['gender'] ?? '';

        $error = $wrapper->signup($username, $email, $password, $birthdate, $given_name, $family_name, $gender);

        if(empty($error)) {
            header('Location: /public/confirm.php?username=' . $username);
            exit;
        }
    }

    if($_POST['action'] === 'login') {
        $error = $wrapper->authenticate($username, $password);

        if(empty($error)) {
            header('Location: /public/secure.php');
            exit;
        }
    }
}

$message = '';
if(isset($_GET['reset'])) {
    $message = 'Your password has been reset. You can now login with your new password';
}
?>

<!doctype html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='x-ua-compatible' content='ie=edge'>
        <title>AWS Cognito App - Register and Login</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    </head>
    <body>
        <h1>Menu</h1>
        <ul>
            <li><a href='/public/'>Index</a></li>
            <li><a href='/public/secure.php'>Secure page</a></li>
            <li><a href='/public/confirm.php'>Confirm signup</a></li>
            <li><a href='/public/forgotpassword.php'>Forgotten password</a></li>
            <li><a href='/public/logout.php'>Logout</a></li>
        </ul>
        <p style='color: red;'><?php echo $error;?></p>
        <p style='color: green;'><?php echo $message;?></p>
        <h1>Register</h1>
        <form method='post' action=''>
            <input type='text' placeholder='Username' name='username' /><br />
            <input type='text' placeholder='Email' name='email' /><br />
            <input type='text' placeholder='Birth date' name='birthdate' value="1985-01-31" /><br />
            <input type='text' placeholder='Given name' name='given_name' value="ฟิวส์"  /><br />
            <input type='text' placeholder='Family name' name='family_name' value="โอริสมา" /><br />
            <input type='text' placeholder='Gender' name='gender' value="male" /><br />
            <input type='password' placeholder='Password' name='password' /><br />
            <input type='hidden' name='action' value='register' />
            <input type='submit' value='Register' />
        </form>

        <h1>Login</h1>
        <form method='post' action=''>
            <input type='text' placeholder='Username' name='username' /><br />
            <input type='password' placeholder='Password' name='password' /><br />
            <input type='hidden' name='action' value='login' />
            <input type='submit' value='Login' />
        </form>
        <p><a href='/public/forgotpassword.php'>Forgot password?</a></p>
    </body>
</html>
