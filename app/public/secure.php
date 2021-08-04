<?php
require '../vendor/autoload.php';

use AWSCognitoApp\AWSCognitoWrapper;

$wrapper = new AWSCognitoWrapper();
$wrapper->initialize();

if(!$wrapper->isAuthenticated()) {
    header('Location: /public');
    exit;
}

$user = $wrapper->getUser();
$pool = $wrapper->getPoolMetadata();
$users = $wrapper->getPoolUsers();
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
            <li><a href='/public'>Index</a></li>
            <li><a href='/public/secure.php'>Secure page</a></li>
            <li><a href='/public/confirm.php'>Confirm signup</a></li>
            <li><a href='/public/forgotpassword.php'>Forgotten password</a></li>
            <li><a href='/public/logout.php'>Logout</a></li>
        </ul>
        <h1>Secure page</h1>
        <p>Welcome <strong><?php echo $user->get('Username');?></strong>! You are succesfully authenticated. Some <em>secret</em> information about this user pool:</p>

        <?php
        $attributes_array = array();
        foreach($user->get('UserAttributes') as $attributes) {
            $attributes_array[$attributes['Name']] = $attributes['Value'];
        }
        // var_dump($attributes_array);
        ?>
        <h2>Metadata</h2>
        <p><b>Id:</b> <?php echo $pool['Id'];?></p>
        <p><b>Sub:</b> <?php echo $user->get('sub');?></p>
        <p><b>Name (Pool):</b> <?php echo $pool['Name'];?></p>
        <p><b>Name (User):</b> <?php echo $attributes_array['sub'];?></p>
        <p><b>Given Name:</b> <?php echo $attributes_array['given_name']?></p>
        <p><b>Family Name:</b> <?php echo $attributes_array['family_name'];?></p>
        <p><b>Birth Date:</b> <?php echo $attributes_array['birthdate'];?></p>
        <p><b>Gender:</b> <?php echo $attributes_array['gender']?></p>
        <p><b>Email:</b> <?php echo $attributes_array['email']?></p>
        <p><b>Email Verified Status:</b> <?php echo $attributes_array['email_verified']?></p>
        <p><b>CreationDate:</b> <?php echo $pool['CreationDate'];?></p>

        <h2>Users in Pool</h2>
        <ul>
        <?php
        foreach($users as $user) {
            $email_attribute_index = array_search('email', array_column($user['Attributes'], 'Name'));
            $email = $user['Attributes'][$email_attribute_index]['Value'];

            echo "<li>{$user['Username']} ({$email})</li>";
        }
        ?>
        </ul>
    </body>
</html>
