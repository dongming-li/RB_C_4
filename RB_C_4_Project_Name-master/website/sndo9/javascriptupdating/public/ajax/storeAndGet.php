<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 8/25/17
 * Time: 9:09 PM
 */

require '../../private/database/database_login.php';


$request = $_REQUEST['action'];

//echo $request . " ";

if($request == 'store'){
    echo store($connect, $_REQUEST['value']);
}

if($request == 'get'){
    get($connect);
}

if($request == 'reset'){
    echo reseted($connect);
}

if($request == 'backspace'){
    backspace($connect);
}

function store($c, $value){

    $query = "";
    $query .= "INSERT INTO log (letter) VALUES " . "('" . "$value" . "')";

    //echo $query;

    //echo "<br>";

    mysqli_query($c, $query);
	echo mysqli_error($c);
    return;
}

function get($c){
    $query = "";

    $query .= "SELECT * FROM log ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($c, $query);

    if($result){

    } else {
        echo "no data";
    }

    $output = "";
    while($letter = mysqli_fetch_assoc($result)){
        $output .= $letter['letter'];
    }

    echo $output;
}

function reseted($c){
    $query = "TRUNCATE log";
    mysqli_query($c, $query);
}

//not currently used
function backspace($c){
    $query = "DELETE FROM log ORDER by id DESC LIMIT 1";

    mysqli_query($c, $query);
}