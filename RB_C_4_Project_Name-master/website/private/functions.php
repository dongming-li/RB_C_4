<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 10/8/17
 * Time: 5:39 PM
 */

function print_r2($val)
{
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}

function initalize_log_file($filename)
{
    $file = fopen($filename, 'w');
    fwrite($file, "");
    fclose($file);
}

function log_output($filename, $output)
{
    file_put_contents($filename, $output, FILE_APPEND);
}

function redirect_to($new_location){
    header("Location: " . $new_location);
    exit;
}