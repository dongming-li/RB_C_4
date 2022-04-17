<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 12/5/17
 * Time: 4:38 AM
 */


require_once '../private/functions.php';
session_start();
session_destroy();

redirect_to('index.php');