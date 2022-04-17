<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 11/26/17
 * Time: 7:03 PM
 */
/**
 * Ajax function to save review to database.
 * @param action defines what task will be preformed.
 * submit_review - saves review to database.
 * @param rating Rating of the review.
 * @param text Content of the review.
 * @param username User that posted the review.
 */
require('../../private/database/database_login.php');
require('../../private/database/client_database.php');

$action = $_REQUEST['action'];

if ($action === "submit_review") {
    $review_rating = $_REQUEST['rating'];
    $review_text = $_REQUEST['text'];
    $username = $_REQUEST['username'];

    echo save_review($connect, $username, $review_text, $review_rating);
}
