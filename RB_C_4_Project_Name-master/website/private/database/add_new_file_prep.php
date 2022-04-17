<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 11/7/17
 * Time: 2:29 PM
 */

/**
 * First checks to ensure file with same project and branch doesn't exist
 * Inserts a new file with certain branch tag into the PROJECTS table in database
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

    $query_add = "INSERT INTO FILES (`branch_id`, `project_id`, `tag`, `layout`, `path`) VALUES(?, ?, 1, '{}', ?)";
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

}

/**
 * Checks to ensure project with same name doesn't already exist
 * Inserts new project into the database
 * Inserts a new branch for the project into the BRANCHES table
 * @param $connect
 * @param $project_name
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
 * Checks to ensure branch with the same project ID and branch tag doesn't already exist
 * Creates new row in BRANCHES table with project ID and branch tag
 * @param $connect
 * @param $project_name
 * @param $branch_tag
 */
function add_new_branch($connect, $project_name, $branch_tag)
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
}