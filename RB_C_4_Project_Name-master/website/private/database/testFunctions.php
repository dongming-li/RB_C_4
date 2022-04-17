<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 10/24/17
 * Time: 10:07 AM
 */

require ('./database_login.php');
require('./create_tables.php');
require('./create_users.php');

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}


// Testing create_tables.php functions
//$temp = check_table_exists($connect, "USERS"); // WORKS
//$temp = create_users_table($connect); // WORKS
// Testing create_users.php functions


// WORKS for all three types of User, both when already existing and non-existing
$temp = add_user($connect, "Jason", "Bourne", "ultimatum@email.com", "jbourne", "bournej", "CLIENT"); // WORKS
echo("<br>");
echo($temp);
$temp = add_user($connect, "Harry", "Potter", "hjp@hogwarts.edu", "hplopper", "iopenattheclose", "MANAGER"); // WORKS
echo("<br>");
echo($temp);
$temp = add_user($connect, "Luke", "Skywalker", "starwars@email.com", "walkerl", "whosmydaddy", "DEVELOPER"); // WORKS
echo("<br>");
echo($temp);
$temp = add_user($connect, "Jack", "Ripper", "yikes@email.com", "jackr", "ripperjack", "DEVELOPER"); // WORKS
echo("<br>");
echo($temp);
// WORKS for both existing and non-existing Users
$temp = checkForExistingUser("alexmort", "password", $connect);
echo("<br>");
echo($temp);
