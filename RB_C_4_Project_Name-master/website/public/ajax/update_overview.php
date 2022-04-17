<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/4/17
 * Time: 3:46 PM
 */

/**
 * Ajax function to update the manager overview and developer pages.
 * @param action Defines what task to preform.
 * get_projects - returns a list of projects.
 * get_branches - returns a list of branches for a given project.
 * get_files - returns a list of files in a given project and branch.
 * overview_get_files - returns a list of files for manager overview.
 * num_users_by_type - returns the number of users by type.
 * @param project id of project being used.
 * @param branch id of branch being used.
 * @param type Defines the type of user being counted.
 */
require '../../private/functions.php';
require '../../private/database/database_login.php';
require  '../../private/database/get_projects.php';

$request = $_REQUEST['action'] ?? 'none';

if($request === 'none')
{
    echo "invalid request";
    //return;
}

if($request === 'get_projects')
{
    $output = "";
    $output .= "<table><tr><th>Project</th></tr>";

    $projects = get_projects($connect);

    $output = "";
    $output .= "<table><tr><th>Project</th></tr>";

    foreach($projects as $project)
    {
        $output .= "<tr><td><p id='$project' class='project-name'>$project</p></td></tr>";
    }
    $output .= "</table>";
    $output .= "<p id=\"new-project\" class=\"files\">Add New...</p>
                <input type=\"text\" id=\"new-project-input\" hidden>";

    echo $output;
}

if($request === 'get_branches')
{
    $output = "Branch: <select id='branch-name'></select>";

    $branches = get_branches($connect, $_REQUEST['project']);

    echo $branches;
}

if($request === 'get_files')
{
    $files = get_files($connect, $_REQUEST['project'], $_REQUEST['branch']);

    echo $files;
}

if($request === 'overview_get_files')
{
    $files = overview_get_files($connect, $_REQUEST['project'], $_REQUEST['branch']);

    echo $files;
}

if($request === 'num_users_by_type')
{
    $type = $_REQUEST['type'];
    echo get_num_users_by_type($connect, $type);
}