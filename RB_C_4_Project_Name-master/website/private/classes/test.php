<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/7/17
 * Time: 5:16 PM
 */

echo "<title>Class test page</title>";

require 'Tuple.php';
require 'Chunker.php';
require 'LameTuple.php';
require '../functions.php';
require '../database/chunker_database.php';
require '../database/database_login.php';


$chunk = new \Cylaborate\Chunker($connect, 'Test Project', 'Test Branch', 'testpath', 'tagtest', 'stuff.people.contentpage.bookofmormon');

//$chunk->add_chunk_to_end("Hello");
//$chunk->add_chunk_to_end("I have a boner for murder");
//$chunk->add_chunk_to_end("You just got sarged");
//$chunk->add_chunk_to_end("I'm from Iowa");

//$chunk->add_chunk_after_id(1, "this book will change your life");


//$chunk->delete_chunk_by_id_and_position(1);

$chunk->get_layout();

//$chunk->save_layout_to_table_();

$chunk->push_new_layout();

//$chunk->get_page_contents();