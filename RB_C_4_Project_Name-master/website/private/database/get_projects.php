<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/4/17
 * Time: 3:54 PM
 */

/**
 * Returns an array of all the projects in the database
 * @param $connect
 * @return array
 */
function get_projects($connect)
{
    $query = 'SELECT * FROM PROJECTS';
    $ret = mysqli_query($connect, $query);

    $retval = array();

    while($val = mysqli_fetch_assoc($ret))
    {
        array_push($retval, $val['project_name']);
    }

    return $retval;
}

/**
 * Returns all of the branches for a certain project ID
 * @param $connect
 * @param $project_id
 * @return string
 */
function get_branches($connect, $project_id)
{
    $query = "SELECT * FROM PROJECTS where `project_name` = '$project_id'";
    $ret = mysqli_query($connect, $query);
    $val = mysqli_fetch_assoc($ret);

    $id = $val['id'];

    $query = "SELECT * FROM BRANCHES WHERE `project_id` = '$id' ORDER BY `id` DESC";
    $ret = mysqli_query($connect, $query);

    $output = "Branch: <select id='branch-options'>";
    $output .= "<option value=''></option>";

    while($val = mysqli_fetch_assoc($ret))
    {
        $branch_name = $val['branch_tag'];
        $output .= "<option value='$branch_name'>$branch_name</option>";
    }

    $output .= "</select>";

    $output .= "<p id=\"new-branch\" class=\"files\">Add New...</p>
                <input type=\"text\" id=\"new-branch-input\" hidden>";

    return $output;

};

/**
 * Returns an HTML string of all the files in a certain branch of a certain project
 * @param $connect
 * @param $project_id
 * @param $branch_id
 * @return string
 */
function get_files($connect, $project_id, $branch_id)
{
    $query = "SELECT * FROM PROJECTS where `project_name` = '$project_id'";
    $ret = mysqli_query($connect, $query);
    $val = mysqli_fetch_assoc($ret);

    $project = $val['id'];

    log_output("log.txt", "project_id: $project \n");

    $query = "SELECT * FROM BRANCHES where `branch_tag` = '$branch_id' AND `project_id` = '$project'";
    $ret = mysqli_query($connect, $query);
    $val = mysqli_fetch_assoc($ret);

    $branch = $val['id'];

    log_output("log.txt", "branch_id: $branch \n");
    log_output("log.txt", mysqli_error($connect));


    $query = "SELECT * FROM FILES WHERE `project_id` = '$project' AND `branch_id` = '$branch'";
    $ret = mysqli_query($connect, $query);

    $output = "<b>Files</b></br>";
    $output .= "<p id=\"new-file\" class=\"files\">Add New...</p>
                <input type=\"text\" id=\"new-file-input\" hidden>";

    while($val = mysqli_fetch_assoc($ret))
    {
        $file_name = $val['path'];
        $output .= "<p class='files' id='$file_name'>$file_name</p>";
    }

    return $output;
}

/**
 * Creates the HTML for displaying all of the files of a certain branch in a certain project
 * Attaches buttons for saving and running
 * @param $connect
 * @param $project_id
 * @param $branch_id
 * @return string
 */
function overview_get_files($connect, $project_id, $branch_id)
{
    $query = "SELECT * FROM PROJECTS where `project_name` = '$project_id'";
    $ret = mysqli_query($connect, $query);
    $val = mysqli_fetch_assoc($ret);

    $project = $val['id'];

    $query = "SELECT * FROM BRANCHES where `branch_tag` = '$branch_id'  AND `project_id` = '$project'";
    $ret = mysqli_query($connect, $query);
    $val = mysqli_fetch_assoc($ret);

    $branch = $val['id'];

    $query = "SELECT * FROM FILES WHERE `project_id` = '$project' AND `branch_id` = '$branch'";
    $ret = mysqli_query($connect, $query);

    $output = "";
    $output .= "<table><tr>";

    while($val = mysqli_fetch_assoc($ret))
    {
        $file_name = $val['path'];
        $query2 = "SELECT * FROM FILES WHERE `project_id` = '$project' AND `branch_id` = '$branch' AND `path` = '$file_name'";
        $ret2 = mysqli_query($connect, $query2);

        $output .= "<td>$file_name</td><td><select id='$file_name'>";

        while($val2 = mysqli_fetch_assoc($ret2))
        {
            $version = $val2['tag'];
            $output .= "<option class='file_version_options' id='$file_name' value'$version'>$version</option>";
        }
        $id = $file_name . "-run";
        $output .= "</td><td><button id='$file_name' class='overview_save_file_version'>Save</button></td><td id='$id'>
                    <button id='$file_name' class='overview_run'>Run</button></td></tr>";
    }

    $output .= "<tr><td></td><td id='overview_download'></td><td><button id='overview_save'>Save all</button></td></tr>";

    $output .= "</table>";

    return $output;
}

/**
 * Returns the count of all users of type MANAGER, DEVELOPER, or CLIENT
 * @param $connect
 * @param $type
 * @return int
 */
function get_num_users_by_type($connect, $type)
{
    $query = "SELECT * FROM USERS WHERE `user_type` = '$type'";
    $ret = mysqli_query($connect, $query);

    error_log(mysqli_error($connect));

    $i = 0;

    while($val = mysqli_fetch_assoc($ret))
    {
        $i++;
    }

    return $i;
}
