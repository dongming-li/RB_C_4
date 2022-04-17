<?php
/**
 * Created by PhpStorm.
 * User: wojoinc
 * Date: 9/24/17
 * Time: 4:51 PM
 */

//$content should contain the relative url for the requested content
$content = $_REQUEST['content'];

if (strtoupper($content) == "OVERVIEW") {
    $file = fopen('../private/page_templates/overview.html', 'r') or die("Unable to open ");
    echo fread($file, filesize("../private/page_templates/overview.html"));
    fclose($file);
}

if (strtoupper($content) == "EDITOR") {
    $file = fopen('../private/page_templates/editor.html', 'r') or die("Unable to open ");
    echo fread($file, filesize("../private/page_templates/editor.html"));
    fclose($file);
}