
<?php
/**
 * Created by PhpStorm.
 * User: rishabkinnerkar
 * Date: 10/13/2017
 * Time: 8:27 PM
 */
$q=$_GET["q"];
// testng on local apache server for now.
$host="localhost"; // Host name , mysql.cs.iastate.edu
$user="root"; // username   db309rbc4
$pass=""; // password      dbu309rbc4
$db_name=""; // Database name     

// Connect to server and select databse.
$link = mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name",$link)or die("cannot select DB");


$sql="SELECT name FROM tblprofile WHERE userId = '".$q."'";

$result = mysql_query($sql);

$row = mysql_fetch_array($result);

$name =$row['name'];

if($name == '' || empty($name)) {
    echo "<b>ID not found.</b>";
} else {
    echo "<b>".$name."</b>";
}

mysql_close($link);
?>