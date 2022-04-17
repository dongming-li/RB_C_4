<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 11/5/17
 * Time: 3:32 PM
 */


/**
 * Pull layout from file, find old chunk id, remove it from array, add new chunk in
 *
 * @param $connection
 * @param $new_id
 * @param $old_id
 * @param $content
 * @return int
 */
function update_chunk($connection, $new_id, $old_id, $content) {
    $pull_layout = "SELECT layout FROM FILES WHERE id=?";
    $stmt = $connection->stmt_init();
    if($stmt->prepare($pull_layout)) {
        $stmt->bind_param("s", $new_id);
        if($stmt->execute()) { // got layout json array
            $ret = $stmt->get_result();
            $layout = $ret->fetch_assoc()['layout'];
            $arr = json_decode($layout, true);
            print_r($arr);
            foreach($arr as $key => $value) {
                echo($key." = ".$old_id."\n");
                if ($key === $old_id) {
                    echo("Match\n");
                }
            }
            echo("DONE");
            $stmt->close();
            return 0;
        } else {
            return -1;
        }
    } else {
        return -1;
    }
}