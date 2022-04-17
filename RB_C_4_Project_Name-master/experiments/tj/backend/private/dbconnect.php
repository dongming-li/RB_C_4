<?php
/**
 * Created by PhpStorm.
 * User: 97wes
 * Date: 9/20/2017
 * Time: 11:22 PM
 */
    $hostname   = "mysql.cs.iastate.edu";
    $db_name    = "db309rbc4";
    $db_user    = "dbu309rbc4";
    $db_pass    = "tt2TCc!f";

    $db = mysqli_connect($hostname,$db_user,$db_pass,$db_name);

    if(mysqli_connect_errno()){
        echo "Failed to connect to database!: " . mysqli_connect_error();
    }