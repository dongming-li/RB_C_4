<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/4/17
 * Time: 5:47 PM
 */

/**
 * Ajax function to get the current username and user level from session
 * @param action What task to do
 * get_username - returns current user name
 * get_user_level - returns current user level
 */
require '../../private/functions.php';

session_start();

$request = $_REQUEST['action'] ?? 'none';
//$_SESSION['username'] = 'nosferatu';

if($request === 'none')
{
    echo "invalid request";
    return;
}

if($request === 'get_username')
{
    echo $_SESSION['username'];
    //return;
}

if($request === 'get_user_level')
{
    initalize_log_file("log.txt");
    log_output("log.txt", 'username: '. $_SESSION['username'] . "\n");
    log_output("log.txt", 'user level: '. $_SESSION['user_level'] . "\n");


    echo $_SESSION['user_level'];
}