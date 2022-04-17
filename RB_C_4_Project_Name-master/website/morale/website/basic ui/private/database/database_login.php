<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 9/24/17
 * Time: 5:37 PM
 */

$host_name  = "mysql.cs.iastate.edu";
$database   = "db309rbc4";
$user_name  = "dbu309rbc4";
$password   = "tt2TCc!f";

$connect = mysqli_connect($host_name, $user_name, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
