<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/8/17
 * Time: 8:53 PM
 */

require 'Tuple.php';
require 'Chunker.php';
require 'LameTuple.php';
require '../functions.php';
require '../database/chunker_database.php';
require '../database/database_login.php';

initalize_log_file("chunker_log.txt");

$chunk = new \Cylaborate\Chunker($connect, 1, 1, 2, "tagtest", "I.HAVE.MAGOTS.IN.MY.SCROTOM");

echo $chunk->get_layout();

echo $chunk->get_page_contents();