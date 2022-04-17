<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 11/30/17
 * Time: 8:37 AM
 */

/**
 * Takes the provided connection to find the id of the user whose username is passed in
 * Uses that user id to store the review information in the database
 * @param $connect
 * @param $username
 * @param $text
 * @param $rating
 * @return bool|void
 */
function save_review($connect, $username, $text, $rating)
{
    $user_id = "-1";
    $query_check = "SELECT `id` FROM USERS WHERE `username`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_check)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $value = $stmt->get_result();
            $stmt->close();
            if ($value->num_rows > 0) {
                $arr = $value->fetch_assoc();
                $user_id = $arr['id'];
            }
        } else {
            echo("Statement could not be executed.");
            return;
        }
    } else {
        echo("Failure to prepare query");
        return;
    }

    $query = "INSERT INTO REVIEWS(`user_id`, `review_text`, `review_rating`) VALUES(?, ?, ?)";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("sss", $user_id, $text, $rating);
        if ($stmt->execute()) {
            return true;
        } else {
            echo("Statement could not be executed\n");
            echo($stmt->error);
            return;
        }
    } else {
        echo("Failure to prepare query");
        return;
    }
}