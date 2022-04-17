<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 10/4/17
 * Time: 3:55 PM
 */

/**
 * Ajax function to control user login.
 * @param action What task to preform.
 * check - checks to see if a user is logged in on this session.
 * login - logs in users if username and password match database, returns logged_in or failed.
 * register - registers users if username does not already exist.
 * logout - destroys session.
 * @param username Username given by the user.
 * @param password Password given by the user.
 * @param email Email address given by the user.
 * @param first First name given by user.
 * @param last Last name given by user.
 * @param type User level chosen by user.
 */
require('../../private/database/database_login.php');
require('../../private/database/create_users.php');
require('../../private/functions.php');

session_start();

$action = $_REQUEST['action'];

if($action == 'check')
{
    if(!isset($_SESSION['username']))
    {
        echo "not_logged_in";
        return;
    }
    else
    {
        echo "logged_in";
        return;
    }
}

if($action === "login")
{
    $username = $_REQUEST['username'];
    //$password = password_hash($_REQUEST['password'], PASSWORD_BCRYPT, ['cost' => 10]);
    $password = $_REQUEST['password'];

    $retval = checkForExistingUser($username, $password, $connect);

    if($retval == 0)
    {
        echo "logged_in";
        return;
    }
    else if($retval == -1)
    {
        echo "failed";
        return;
    }
}

if($action === "register")
{
    $username = $_REQUEST['username'];
    //Robert: broken, don't know why right now.
    //$password = password_hash($_REQUEST['password'], PASSWORD_BCRYPT, ['cost' => 10]);
    $email    = $_REQUEST['email'];
    $firstname= $_REQUEST['first'];
    $lastname = $_REQUEST['last'];
    $type     = $_REQUEST['type'];

    $password = $_REQUEST['password'];

    $retval = add_user($connect, $firstname, $lastname, $email, $username, $password, $type);

    if($retval == -1)
    {
        echo "Error, please contact an administrator";
    }
    else if($retval == 1)
    {
        echo "Username already in use";
    }
    else if($retval == 0)
    {
        echo "Logged in";
        $_SESSION['username'] = $username;
        if($type === 'client') $_SESSION['user_level'] = 0;
        if($type === 'developer') $_SESSION['user_level'] = 1;
        if($type === 'manager') $_SESSION['user_level'] = 2;
    }
    else
    {
        echo $retval;
    }

}

if($action === 'logout')
{
    session_destroy();
}

