<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/5/17
 * Time: 11:23 PM
 */

/**
 * Ajax program to create new files, branches, and projects.
 * @param action task for ajax to preform
 * new_file - creates a new file in the given branch and project
 * new_project - creates a new project.
 * new_branch - creates a new branch in the given project.
 * @param project project to be created in.
 * @param branch branch for file to be created in.
 * @param filename name of file to be created.
 * @param project_name name of project to be created.
 * @param branch_name name of branch to be created.
 * @param current_branch name of current branch before new branch is created.
 */
require '../../private/functions.php';
require '../../private/database/database_login.php';
require '../../private/database/add_new_file.php';

session_start();

$request = $_REQUEST['action'] ?? 'none';
//$_SESSION['username'] = 'nosferatu';

if($request === 'none')
{
    echo "invalid request";
    return;
}

if($request === 'new_file')
{
    echo add_file($connect, $_REQUEST['project'], $_REQUEST['branch'], $_REQUEST['filename']);
}

if($request === 'new_project')
{
    echo add_new_project($connect, $_REQUEST['project_name']);
}

if($request === 'new_branch')
{
    echo add_new_branch($connect, $_REQUEST['project_name'], $_REQUEST['branch_name'], $_REQUEST['current_branch']);
}