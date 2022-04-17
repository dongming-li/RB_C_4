<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 10/8/17
 * Time: 11:03 PM
 */

/**
 * @param $connect
 */
function fix_database($connect)
{
    $query = "UPDATE `db309rbc4`.`LAYOUTS` SET `chunks`='f6adba05607e0db0c345f4f5e3a13dbad9c017d837ba481da7648d82b35f556e987ae7868a05d3741da5fea6da0d50d8bb13f8fbe871c60aab0285e4e4fb4c78,19f5894b9184899aadcb58f1be35c8db520295afb9cea58f553fe83ce8268d584391ae44049b64d2735c6a411a03b756deb6080e64b2b73f36a29353d536e497' WHERE `id`='2'";
    mysqli_query($connect, $query);
}