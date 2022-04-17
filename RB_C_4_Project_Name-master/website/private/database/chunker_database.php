<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/6/17
 * Time: 2:01 AM
 * @param $connect
 * @param $project_name
 * @return int
 */

/**
 * Returns the id for a project given the project name
 * @param $connect
 * @param $project_name
 * @return int : id
 */
function get_project_id($connect, $project_name)
{
    $query_proj_id = "SELECT `id` FROM PROJECTS WHERE `project_name` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_proj_id)) {
        $stmt->bind_param("s", $project_name);
        if ($stmt->execute()) {
            $ret_val = $stmt->get_result();
            $ret_arr = $ret_val->fetch_assoc();
            return $ret_arr['id'];
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Statement could not prepare query");
        return -1;
    }
}

/**
 * Returns the id for a branch given the branch name
 * @param $connect
 * @param $project_id
 * @param $branch_name
 * @return int
 */
function get_branch_id($connect, $project_id, $branch_name)
{
    $query_branch_id = "SELECT `id` FROM BRANCHES WHERE `branch_tag` = ? AND `project_id` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_branch_id)) {
        $stmt->bind_param("ss", $branch_name, $project_id);
        if ($stmt->execute()) {
            $ret_val = $stmt->get_result();
            $ret_arr = $ret_val->fetch_assoc();
            return $ret_arr['id'];
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Statement could not prepare query");
        return -1;
    }
}

/**
 * Uses the project name and branch tag to get IDs, then uses those to return a
 * JSON encoded string of all the tags in that version of a file
 * @param $connect
 * @param $project_name
 * @param $branch_tag
 * @param $file_path
 * @return int|string
 */
function get_version_tags($connect, $project_name, $branch_tag, $file_path)
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

    $branch_id = -1;
    $query_get_id = "SELECT `id` FROM BRANCHES WHERE `project_id`=? AND `branch_tag` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_get_id)) {
        $stmt->bind_param("ss", $project_id, $branch_tag);
        if ($stmt->execute()) {
            $val = $stmt->get_result();
            $arr = $val->fetch_assoc();
            $branch_id = $arr['id'];
        } else {
            log_output("log.txt", $stmt->error);
            return;
        }
    } else {
        log_output("log.txt", $stmt->error);
        return;
    }

    $query_version_tags = "SELECT `tag` FROM FILES WHERE `project_id` = ? AND`branch_id` = ? AND  `path` = ?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_version_tags)) {
        $stmt->bind_param("sss", $project_id, $branch_id, $file_path);
        if ($stmt->execute()) {
            $ret_val = $stmt->get_result();
            $ret_arr = array();
            for ($i = 0; $i < $ret_val->num_rows; $i++) {
                $temp = $ret_val->fetch_assoc()['tag'];
                $ret_arr[] = $temp;
            }
            return json_encode($ret_arr);
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Statement could not prepare query");
        return -1;
    }

}

/**
 * Returns the ordered layout of chunks for a file with the given project ID, branch ID,
 * filename, and version tag
 * @param $connect
 * @param $project_id
 * @param $branch_id
 * @param $file_name
 * @param $version_tag
 * @return int
 */
function get_file_layout($connect, $project_id, $branch_id, $file_name, $version_tag)
{
    $query_file_layout = "SELECT `layout` FROM FILES WHERE `branch_id` = '$branch_id' AND `project_id` = '$project_id' 
      AND `path` = '$file_name' AND `tag` = '$version_tag'";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_file_layout)) {
        $stmt->bind_param("ssss", $branch_id, $project_id, $file_name, $version_tag);
        if ($stmt->execute()) {
            $ret_val = $stmt->get_result();
            $ret_arr = $ret_val->fetch_assoc();
            return $ret_arr;
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Statement could not prepare query");
        return -1;
    }
}

/**
 * Returns the string contents of a certain chunk with given ID
 * @param $connect
 * @param $chunk_id
 * @return mixed: contents on success, -1 on failure
 */
function get_chunk_contents($connect, $chunk_id)
{
    $query = "SELECT `contents` FROM CHUNKS WHERE `id`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("s", $chunk_id);
        if ($stmt->execute()) {
            $ret = $stmt->get_result();
            $val = $ret->fetch_assoc();
            return $val['contents'];
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Statement could not prepare query.");
        return -1;
    }
}

/**
 * Checks to see if a certain chunk with the given ID exists in the database
 * @param $connect
 * @param $chunk_id
 * @return int: 1 if chunk exists, 0 if not
 */
function does_chunk_exist_by_id($connect, $chunk_id)
{
    $query = "SELECT * FROM CHUNKS WHERE `id`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("s", $chunk_id);
        if ($stmt->execute()) {
            $ret = $stmt->get_result();
            if ($ret->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Could not prepare query.");
        return -1;
    }
}

/**
 * Inserts a new chunk into the database with version tag 'latest' to indicate originality
 * @param $connect
 * @param $chunk_id
 * @param $content
 */
function add_chunk($connect, $chunk_id, $content)
{
    $query = "INSERT INTO CHUNKS (`id`, `tag`, `create_date`,`last_modified`, `contents` )
              VALUES(?, 'latest', NULL, NULL, ?)";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("ss", $chunk_id, $content);
        if (!$stmt->execute()) {
            log_output("chunker_log.txt", $stmt->error . "\n");
        }
    } else {
        echo("Could not prepare query.");
    }
}

/**
 * Checks for the existence of a file in the database
 * @param $connect
 * @param $file
 * @param $project
 * @param $branch
 * @param $version_tag
 * @return int
 */
function does_file_exist($connect, $file, $project, $branch, $version_tag)
{
    $query = "SELECT * FROM FILES WHERE `path`=? AND `project_id`=? AND `branch_id`=? AND `tag`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("ssss", $file, $project, $branch, $version_tag);
        if ($stmt->execute()) {
            $ret = $stmt->get_result();
            if ($ret->num_rows > 0) {
                return 1;
            } else {
                return 4;
            }
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        log_output("chunker_log.txt", $stmt->error . "\n");
        return -2;
    }
}


/**
 * Returns the file's chunk layout specifications
 * @param $connect
 * @param $file
 * @param $project
 * @param $branch
 * @param $version_tag
 * @return mixed : layout on success, -1 if doesn't exist
 */
function get_file_layout_from_table($connect, $file, $project, $branch, $version_tag)
{
    $query = "SELECT `layout` FROM FILES WHERE `path`=? AND `project_id`=? AND `branch_id`=? AND `tag`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("ssss", $file, $project, $branch, $version_tag);
        if ($stmt->execute()) {
            $ret = $stmt->get_result();
            if ($ret->num_rows > 0) {
                $val = $ret->fetch_assoc();
                return $val['layout'];
            } else {
                return -1; // Somehow, file layout doesn't exist
            }
        } else {
            log_output("chunker_log.txt", $stmt->error . "\n");
            return -1;
        }
    } else {
        echo("Could not prepare query.");
        return -1;
    }
}

/**
 * Updates file's chunk layout specification
 * @param $connect
 * @param $layout
 * @param $project
 * @param $branch
 * @param $file
 * @param $version_tag
 */
function save_layout_to_table($connect, $layout, $project, $branch, $file, $version_tag)
{
    $query_update = "UPDATE FILES SET `layout`=? WHERE `project_id`=? AND `branch_id`=? AND `path`=? AND `tag`=?";
    $stmt = $connect->stmt_init();
    if ($stmt->prepare($query_update)) {
        $stmt->bind_param("sssss", $layout, $project, $branch, $file, $version_tag);
        if (!$stmt->execute()) {
            log_output("chunker_log.txt", $stmt->error . "\n");
        }
    } else {
        log_output("chunker_log.txt", "Could not prepare query.");
    }
}