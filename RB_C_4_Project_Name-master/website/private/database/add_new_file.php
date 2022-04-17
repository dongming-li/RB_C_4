<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/5/17
 * Time: 11:23 PM
 */

/**
 * Gets IDs for the given project and branch
 * Checks to ensure file doesn't already exist
 * Inserts row into FILES table
 * @param $connect
 * @param $project_name
 * @param $branch_tag
 * @param $filename
 */
function add_file($connect, $project_name, $branch_tag, $filename)
{
    $project_id = -1;
    $branch_id = -1;

    $query_proj = "SELECT * FROM PROJECTS WHERE `project_name` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_proj)) {
        $stmt->bind_param("s", $project_name);
        if ($stmt->execute()) {
            $ret_arr = $stmt->get_result();
            $ret_val = $ret_arr->fetch_assoc();
            $project_id = $ret_val['id'];
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error . "\n");
        return;
    }

    $query_branch = "SELECT * FROM BRANCHES WHERE `branch_tag` = ? AND `project_id` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_branch)) {
        $stmt->bind_param("ss", $branch_tag, $project_id);
        if ($stmt->execute()) {
            $ret_arr = $stmt->get_result();
            $ret_val = $ret_arr->fetch_assoc();
            $branch_id = $ret_val['id'];
        } else {
            log_output("log.txt", $stmt->error . "\n");
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    $query_select = "SELECT * FROM FILES WHERE `project_id` = ? AND `branch_id` = ? AND `path` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_select)) {
        $stmt->bind_param("sss", $project_id, $branch_id, $filename);
        if ($stmt->execute()) {
            $ret_arr = $stmt->get_result();
            if ($ret_arr->num_rows > 0) {
                log_output("log.txt", $query_select . "\n");
                return;
            }
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    $query_add = "INSERT INTO FILES (`branch_id`, `project_id`, `tag`, `layout`, `path`) VALUES(?, ?, 1, '[\"cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e\"]', ?)";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_add)) {
        $stmt->bind_param("sss", $branch_id, $project_id, $filename);
        if ($stmt->execute()) {
            echo("Success");
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    error_log("error" . $stmt->error, 0);

}


/**
 * Checks to ensure a project with the same name doesn't already exist
 * Inserts new row into the PROJECTS table
 * Inserts new row into BRANCHES table as the default for that project
 * @param $connect
 * @param $project_name
 * @return string
 */
function add_new_project($connect, $project_name)
{
    $query_check = "SELECT * FROM PROJECTS WHERE `project_name` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_check)) {
        $stmt->bind_param("s", $project_name);
        if ($stmt->execute()) {
            $val = $stmt->get_result();
            if ($val->num_rows > 0) {
                log_output("log.txt", $query_check . "\n");
            }
        } else {
            log_output("log.txt", $stmt->error);
        }
    } else {
        log_output("log.txt", $stmt->error);
    }

    $query_insert = "INSERT INTO PROJECTS (`project_name`) VALUES (?)";
    if ($stmt->prepare($query_insert)) {
        $stmt->bind_param("s", $project_name);
        if (!$stmt->execute()) {
            log_output("log.txt", $stmt->error);
        }
    }

    $project_id = -1;
    $query_id = "SELECT `id` FROM PROJECTS WHERE `project_name`=?";
    if ($stmt->prepare($query_id)) {
        $stmt->bind_param("s", $project_name);
        if ($stmt->execute()) {
            $val = $stmt->get_result();
            $arr = $val->fetch_assoc();
            $project_id = $arr['id'];
            echo $project_id;
        } else {
            log_output("log.txt", $stmt->error);
        }
    } else {
        log_output("log.txt", $stmt->error);
    }

    $query_add_branch = "INSERT INTO BRANCHES (`branch_tag`, `project_id`) VALUES ('master', ?)";
    if ($stmt->prepare($query_add_branch)) {
        $stmt->bind_param("s", $project_id);
        if (!$stmt->execute()) {
            echo "FAIL";
            log_output("log.txt", $stmt->error);
        }
    }
}

/**
 * Inserts new row into BRANCHES table for given project ID
 * Inserts new row into FILES table as default for this branch
 * @param $connect
 * @param $project_name
 * @param $branch_tag
 * @param $old_branch
 */
function add_new_branch($connect, $project_name, $branch_tag, $old_branch)
{
    $project_id = -1;
    $query_get_id = "SELECT `id` FROM PROJECTS WHERE `project_name` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_get_id)) {
        $stmt->bind_param("s", $project_name);
        if ($stmt->execute()) {
            $val = $stmt->get_result();
            $arr = $val->fetch_assoc();
            $project_id = $arr['id'];
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    $query_check_branch = "SELECT * FROM BRANCHES WHERE `project_id` = ? AND `branch_tag` = ?";
    if ($stmt->prepare($query_check_branch)) {
        $stmt->bind_param("ss", $project_id, $branch_tag);
        if ($stmt->execute()) {
            $val = $stmt->get_result();
            if ($val->num_rows > 0) {
                return "branch exists";
            }
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    $query_insert = "INSERT INTO BRANCHES (`branch_tag`, `project_id`) VALUES (?, ?)";
    if ($stmt->prepare($query_insert)) {
        $stmt->bind_param("ss", $branch_tag, $project_id);
        if (!$stmt->execute()) {
            log_output("log.txt", $stmt->error);
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }
    log_output("log.txt", $query_insert . " " . $branch_tag . " " . $project_id);

    if (!($old_branch === 'none')) {
        $query = "SELECT * FROM BRANCHES WHERE `branch_tag` = '$old_branch' AND `project_id` = $project_id";
        $ret = mysqli_query($connect, $query);

        $val = mysqli_fetch_assoc($ret);

        $old_branch_id = $val['id'];

        error_log($old_branch_id, 0);

        $query = "SELECT * FROM BRANCHES WHERE `branch_tag` = '$branch_tag' AND `project_id` = $project_id";
        $ret = mysqli_query($connect, $query);

        $val = mysqli_fetch_assoc($ret);

        $new_branch_id = $val['id'];

        error_log($new_branch_id, 0);


        $query = "SELECT * FROM FILES WHERE `branch_id` = '$old_branch_id' AND `project_id` = '$project_id'";
        $ret = mysqli_query($connect, $query);

        error_log(mysqli_error($connect), 0);

        while ($val = mysqli_fetch_assoc($ret)) {
            //$b_id = $val['branch_id'];
            $p_id = $val['project_id'];
            $tag = $val['tag'];
            $layout = $val['layout'];
            $path = $val['path'];
            $query = "INSERT INTO FILES (`branch_id`, `project_id`, `tag`, `layout`, `path`) VALUES ('$new_branch_id', '$p_id', '$tag', '$layout', '$path')";
            mysqli_query($connect, $query);
        }
    }
}