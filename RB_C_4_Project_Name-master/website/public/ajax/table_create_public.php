<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 9/29/17
 * Time: 12:50 PM
 */

$table_name = $_REQUEST['table'];

require '../../private/database/database_login.php';
require '../../private/database/create_tables.php';

private_create($connect, $table_name);
