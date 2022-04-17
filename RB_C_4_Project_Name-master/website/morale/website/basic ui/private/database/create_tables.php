<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 9/24/17
 * Time: 5:38 PM
 */

require './database_login.php';

//$host_name  = "mysql.cs.iastate.edu";
//$database   = "db309rbc4";
//$user_name  = "dbu309rbc4";
//$password   = "tt2TCc!f";
//
//$connect = mysqli_connect($host_name, $user_name, $password, $database);
//if (mysqli_connect_errno())
//{
//    echo "Failed to connect to MySQL: " . mysqli_connect_error();
//}



$request = $_REQUEST['CREATE'];

if(!$connect) {
    die("Connection failed: " .mysqli_connect_error());
}

//if($request == 'store'){
//    create_tables($connect);
//}

$query = 'SELECT 1 FROM db309rbc4.CHUNKS LIMIT 1';

$val = mysqli_query($connect,$query);

echo($val);

if($val !== FALSE)
{
    echo("Table already exists!\n");
}
else // create chunk table in database
{
    echo "Creating table";
    $create_query = 'CREATE TABLE CHUNKS (
          ChunkID varchar(64)
          )';
    mysqli_query($connect, $create_query);
}


//
//create_tables($connect);
//
//function create_tables($connection) {
//    echo("Writing in create tables");
//
//    $query = "";
//    $query .= 'SELECT 1 FROM db309rbc4.Chunks LIMIT 1;';
//
//    $val = mysqli_query($connection,$query);
//
//    echo($val);
//
//    if($val !== FALSE)
//    {
//        // Do nothing, table exists
//    }
//    else // create chunk table in database
//    {
//        $create_query = 'CREATE TABLE Chunks (
//          ChunkID varchar(64)
//          );';
//        mysqli_query($connection, $create_query);
//    }
//
//}
