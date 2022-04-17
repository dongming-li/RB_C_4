<?php
/**
 * Created by PhpStorm.
 * User: 97wes
 * Date: 11/5/2017
 * Time: 1:16 PM
 */

/**
 * Ajax function for interacting with file layouts.
 * @param request Function the ajax should preform.
 * get_version_tags - returns all version tags for a given file.
 * layout - creates a layout object for a file.
 * snapshot - saves project to server disk.
 * download - downloads a zip of the snapshotted files.
 * run - Runs given file.
 * @param action Sub function the layout should preform.
 * add_to_end - Adds content to the end of a file.
 * add_to_position - Adds content to the middle of a file at a given position.
 * update_position - Changes position of the content in a layout.
 * delete - Removes content from layout.
 * get_page - Returns the current page to the user.
 * @param project Project of the file to be looked up.
 * @param branch Branch in the project of the file to be looked up.
 * @param file File in the branch and project to be looked up.
 * @param version Version of the file to be gotten.
 * @param context Context for updates to be broadcast on.
 * @param content Text to be added to the file.
 * @param position Location of text to be added.
 * @param id of the content to be replaced.
 */

require '../../private/database/database_login.php';
require("../../private/database/db_helper.php");
require '../../private/database/chunker_database.php';
require '../../private/classes/Chunker.php';
require '../../private/classes/LameTuple.php';
require '../../private/functions.php';


/**
 * Handle ajax requests for the chunker here
 * Contains code from ratchet tutorial
 */

$function = $_REQUEST['request'] ?? 'none';
$action = $_REQUEST['action'] ?? 'none';

if($function === 'get_version_tags')
{
    echo get_version_tags($connect, $_REQUEST['project'], $_REQUEST['branch'], $_REQUEST['file']);
}

if ($function === 'layout')
{
    $layout = new \Cylaborate\Chunker(
        $connect,
            $_REQUEST['project'] ?? 'none',
            $_REQUEST['branch'] ?? 'none',
            $_REQUEST['file'] ?? 'none',
            $_REQUEST['version'] ?? 'none',
            $_REQUEST['channel']) ?? 'none';

    $layout->get_layout();

    if ($action === 'add_to_end')
    {
        $layout->add_chunk_to_end($_REQUEST['content']);
        $layout->push_new_layout();
        $layout->save_layout_to_table_();
    }

    if ($action === 'add_to_position')
    {
        $layout->add_chunk_after_id($_REQUEST['position'], $_REQUEST['content']);
        $layout->push_new_layout();
        $layout->save_layout_to_table_();
    }

    if ($action === 'update_position')
    {
        log_output("log.txt", "Updating\n");
        $ret = $layout->update_by_position_and_id($_REQUEST['position'], $_REQUEST['id'], $_REQUEST['content']);
        log_output("log.txt", "update _value = $ret\n");
        $layout->push_new_layout();
        $layout->save_layout_to_table_();
    }

    if($action === 'delete')
    {
        $layout->delete_chunk_by_id_and_position($_REQUEST['position']);
        $layout->push_new_layout();
        $layout->save_layout_to_table_();
    }

    if ($action === 'get_page')
    {
        //echo $layout->get_page_contents();
        $layout->push_new_layout();
    }

}

if($function === 'snapshot')
{
    //error_log($_REQUEST['versions'], 0);
    //print_r2(json_decode($_REQUEST['versions']));

    $versions = json_decode($_REQUEST['versions']);

    $timestamp = date("Y-m-d h-i-sa");



    foreach($versions as $filename => $version)
    {
        $layout = new \Cylaborate\Chunker(
                $connect,
                $_REQUEST['project'] ?? 'none',
                $_REQUEST['branch'] ?? 'none',
                $filename ?? 'none',
                $version ?? 'none',
                null);

        $layout->get_layout();
        $layout->save_to_disk($timestamp);
        break;
    }
    echo $timestamp;
}

if($function === 'download')
{
    $timestamp = $_REQUEST['timestamp'];
    $project = $_REQUEST['project'];
    $branch = $_REQUEST['branch'];

    $destination = escapeshellarg("/var/www/html/public/downloads/$timestamp.zip");
    $target = escapeshellarg("/var/www/html/projects/$project/$branch/$timestamp/");
    $cmd = escapeshellcmd("zip $destination $target");

    error_log($cmd, 0);
    error_log(shell_exec($cmd), 0);

}

if($function === 'run')
{
    $timestamp = $_REQUEST['timestamp'];
    $project = $_REQUEST['project'];
    $branch = $_REQUEST['branch'];
    $filename = $_REQUEST['filename'];

    $target = escapeshellarg("../../projects/$project/$branch/$timestamp/$filename");
    $cmd = escapeshellcmd("javac $target");

    error_log($cmd, 0);
    error_log(shell_exec($cmd), 0);

    $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);

    $target = escapeshellarg("$filename");
    $cmd = escapeshellcmd("java $target");

    chdir("../../projects/$project/$branch/$timestamp");

    error_log($cmd, 0);

    echo shell_exec($cmd);
}

//not used
if($function === 'get_size')
{
    $project = $_REQUEST['project'];

    $f = "../../projects/$project";
    $io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
    $size = fgets ( $io, 4096);
    $size = substr ( $size, 0, strpos ( $size, "\t" ) );
    pclose ( $io );
    echo 'Directory: ' . $f . ' => Size: ' . $size;
}
