<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 10/31/17
 * Time: 8:12 AM
 */


$host_name  = "mysql.cs.iastate.edu";
$database   = "db309rbc4";
$user_name  = "dbu309rbc4";
$password   = "tt2TCc!f";

//global $connect;
$connect = mysqli_connect($host_name, $user_name, $password, $database);

//get_project_branches($connect, '23');
//update_file_contents($connect, '{}', '1');
//get_file_contents($connect, '1');
//get_file_branch($connect, '1', '');
//add_branch($connect, 'branch_usa', 53);

// Works
function get_project_branches($connection, $project_id) {
    $query_get_branches = "SELECT * FROM BRANCHES WHERE project_id=?";
    $stmt = $connection->stmt_init();
    if($stmt->prepare($query_get_branches)) {
        $stmt->bind_param("s", $project_id);
        if ($stmt->execute()) {
            echo ("Success!");
            $val = $stmt->get_result();
            echo($val->num_rows);
        } else {
            echo("Statement failed to execute!");
        }
        $stmt->close();
    } else {
        echo("Failure to prepare query!");
        return;
    }
}

// Works
function update_file_contents($connection, $value, $file_id) {
    $query_update = "UPDATE FILES SET layout=? WHERE id=?";
    $stmt = $connection->stmt_init();
    if($stmt->prepare($query_update)) {
        $stmt->bind_param("ss", $value, $file_id);
        if($stmt->execute()) {
            echo("SUCCESS");
        } else {
            echo("FAILURE");
        }
        $stmt->close();
    } else {
        echo("Failure to prepare query");
        return;
    }
}

//Doesn't work
function get_file_contents($connection, $file_id) {
    $query_file_contents = "SELECT FILES.layout FROM FILES WHERE FILES.id=?";
    $stmt = $connection->stmt_init();
    if ($stmt->prepare($query_file_contents)) {
        echo("HERER<br>");
        $stmt->bind_param("s", $file_id);
        echo("HERER<br>");
        $stmt->execute();
        echo("HERER<br>");
        $ret = $stmt->get_result();
        echo("HERER<br>");
        $stmt->close();
        echo($ret->num_rows);
        echo("HERE1");
    } else {
        echo("Failure to prepare query<br>");
        return;
    }
}

// Works
function get_file_branch($connection, $branch_id, $file_path) {
    $get_guery =
        "SELECT FILES.id FROM FILES
        INNER JOIN BRANCHES
        ON FILES.branch_id = BRANCHES.id 
        WHERE BRANCHES.id=?
        AND FILES.path=?";
    $stmt = $connection->stmt_init();
    if($stmt->prepare($get_guery)) {
        echo("Prepared query<br>");
        $stmt->bind_param("ss", $branch_id, $file_path);
        if(!$stmt->execute()) {
            die("Statement could not be executed!<br>");
        } else {
            echo("Success!<br>");
        }
        $stmt->close();
    }
    else {
        die("Query could not be prepared!<br>");
    }
}

// Works
function add_branch($connection, $branch_tag, $project_id) {
    $query_add = "INSERT INTO BRANCHES (branch_tag, project_id) VALUES (?,?)";
    $stmt = $connection->stmt_init();
    if($stmt->prepare($query_add)) {
        $stmt->bind_param("ss", $branch_tag, $project_id);
        if ($stmt->execute()) {
            echo ("Success!");
        } else {
            echo("Statement failed to execute!");
        }
        $stmt->close();
    } else {
        echo("Failure to prepare query!");
    }
}