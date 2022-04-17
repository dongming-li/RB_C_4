<?php
/**
 * Created by PhpStorm.
 * User: wojoinc
 * Date: 9/24/17
 * Time: 4:51 PM
 */

/**
 * Ajax function to send html pages from server to client.
 * @param content Which html file to load
 * @return content of html file.
 */

//$content should contain the relative url for the requested content
if(isset($_REQUEST['content'])) $content = $_REQUEST['content'];
if(isset($_REQUEST['layout'])) $layout_file = $_REQUEST['layout'];
if(isset($_REQUEST['project'])) $loaded_project = $_REQUEST['project'];
if(isset($_REQUEST['mode'])) $mode = $_REQUEST['mode'];

//if(isset($layout_file)) {
//    if (strtoupper($layout_file) != "") {
//        if (strtoupper($mode) == "READ") {
//            $filepath_layout = '../../projects/' . $loaded_project . "/layouts/" . $layout_file;
//            $file = fopen($filepath_layout, "r");
//            echo fread($file, filesize($filepath_layout));
//            fclose($file);
//        }
//    }
//}

if(isset($content)) {
    if (strtoupper($content) == "OVERVIEW") {
        $file = fopen('../overview.html', 'r') or die("Unable to open ");
        echo fread($file, filesize("../overview.html"));
        fclose($file);
    }

    if (strtoupper($content) == "EDITOR") {
        $file = fopen('../editor.html', 'r') or die("Unable to open ");
        echo fread($file, filesize("../editor.html"));
        fclose($file);
    }

    if (strtoupper($content) == "LOGIN") {
        $file = fopen('../login.html', 'r') or die("Unable to open ");
        echo fread($file, filesize("../login.html"));
        fclose($file);
    }
}