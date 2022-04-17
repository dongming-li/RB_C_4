<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 9/24/17
 * Time: 5:38 PM
 */

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Takes the table name from the front end code and calls the corresponding function
 * to create that table in the database
 * @param $connection
 * @param $table_name
 */
function private_create($connection, $table_name)
{
    if (check_table_exists($connection, $table_name) > 0) {
        echo("$table_name table exists.");
    } else {
        switch ($table_name) {
            case "CHUNKS":
                create_chunks_table($connection);
                break;
            case "USERS":
                create_users_table($connection);
                break;
            case "TEAMS":
                create_teams_table($connection);
                break;
            case "FILES":
                create_files_table($connection);
                break;
            case "PROJECTS":
                create_projects_table($connection);
                break;
            case "ALL": // create all tables in necessary order
                create_users_table($connection);
                create_teams_table($connection);
                create_projects_table($connection);
                create_files_table($connection);
                create_chunks_table($connection);
                break;
            default:
                echo "Table name not recognized\n";
        }
    }
}

/**
 * @param $connection
 * @param $table
 * Queries database to see if database table $table exists
 * @return int|void : returns 1 if table exists, null if not
 */
function check_table_exists($connection, $table)
{
    $query_check_table = "SELECT * FROM $table";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($query_check_table)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    $val = $stmt->get_result();
    $stmt->close();
    if ($val->num_rows > 0) { // If table exists
        return 1;
    } else { // Table does not exist yet
        return 0;
    }
}

/**
 * Creates USERS table in database
 * @param $connection
 */
function create_users_table($connection)
{
    $create_query = "CREATE TABLE USERS (
    id INT UNIQUE AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(60),
    last_name VARCHAR(60),
    email VARCHAR(60),
    username VARCHAR(60),
    password VARCHAR(60)
)";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($create_query)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    if ($stmt->errno != 0) {
        echo("ERROR<br>");
        print_r2($stmt->error_list);
    }
    $val = $stmt->affected_rows;
    return $val;
    $stmt->close();
}

/**
 * Creates FILES table in database
 * @param $connection
 */
function create_files_table($connection)
{
    $create_query = "CREATE TABLE FILES (
	id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(50),
    project_id INT,
    FOREIGN KEY (project_id) REFERENCES PROJECTS(id)
)";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($create_query)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    $val = $stmt->get_result();
    $stmt->close();

    if (mysqli_query($connection, $create_query)) {
        echo("Created Table FILES\n");
    } else { // Query failed
        die("Failure creating FILES table");
    }
}

/**
 * Creates TEAMS table in database
 * @param $connection
 */
function create_teams_table($connection)
{
    $create_query = "CREATE TABLE TEAMS (
	id INT UNIQUE AUTO_INCREMENT PRIMARY KEY,
    manager_id INT,
    FOREIGN KEY (manager_id) REFERENCES MANAGERS(id)
)";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($create_query)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    $val = $stmt->get_result();
    $stmt->close();

    if (mysqli_query($connection, $create_query)) {
        echo("Created Table TEAMS\n");
    } else { // Query failed
        die("Failure creating TEAMS table");
    }
}

/**
 * Creates PROJECTS table in database
 * @param $connection
 */
function create_projects_table($connection)
{
    $create_query = "CREATE TABLE PROJECTS (
	id INT UNIQUE PRIMARY KEY,
	project_name VARCHAR(100),
    manager_id INT,
    team_id INT,
	FOREIGN KEY (manager_id) REFERENCES MANAGERS(id),
    FOREIGN KEY (team_id) REFERENCES TEAMS(id)
)";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($create_query)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    $val = $stmt->get_result();
    $stmt->close();
    if (mysqli_query($connection, $create_query)) {
        echo("Created Table PROJECTS\n");
    } else { // Query failed
        die("Failure creating PROJECTS table");
    }
}

/**
 * Creates CHUNKS table in database
 * @param $connection
 */
function create_chunks_table($connection)
{
    $create_query = "CREATE TABLE CHUNKS (
    id VARCHAR(64) UNIQUE PRIMARY KEY,
    tag VARCHAR(25),
    create_date DATE,
    last_modified DATE,
    developer_id INT,
    layout_file_id INT,
    FOREIGN KEY (developer_id) REFERENCES DEVELOPERS(id)
)";
    $stmt = $connection->stmt_init();
    if (!$stmt->prepare($create_query)) {
        echo("Failure to prepare query");
        return;
    }
    $stmt->execute();
    $val = $stmt->get_result();
    $stmt->close();
    if (mysqli_query($connection, $create_query)) {
        echo("Created Table CHUNKS\n");
    } else { // Query failed
        die("Failure creating CHUNKS table");
    }
}

