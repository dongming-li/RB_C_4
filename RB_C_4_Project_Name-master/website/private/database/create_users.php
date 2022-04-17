<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 10/2/17
 * Time: 5:48 PM
 */

//require("../functions.php");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * Checks to ensure a user with the same username doesn't exist
 * Inserts new row into USERS table with given parameters
 * @param $connection
 * @param $first_name
 * @param $last_name
 * @param $email
 * @param $username
 * @param $password
 * @param $user_type
 */
function add_user(
    $connection,
    $first_name,
    $last_name,
    $email,
    $username,
    $password,
    $user_type
)
{
    $query_check = "SELECT * FROM USERS WHERE username=?";
    $stmt = $connection->stmt_init();
    if ($stmt->prepare($query_check)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $value = $stmt->get_result();
            $stmt->close();
            if ($value->num_rows > 0) {
                echo("User already exists");
                return;
            }
        } else {
            echo("Statement could not be executed.");
            return;
        }
    } else {
        echo("Failure to prepare query");
        return;
    }

    // User does not exist, insert into Users table
    $query_insert = "INSERT INTO USERS (`first_name`, `last_name`, `email`, `username`, `password`, `user_type`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $connection->stmt_init();
    if ($stmt_insert->prepare($query_insert)) {
        $stmt_insert->bind_param("ssssss", $first_name, $last_name, $email, $username, $password, $user_type);
        if ($stmt_insert->execute()) {
            $stmt_insert->close();
            echo "User created successfully!<br>";
        }
    } else {
        echo("Failure to prepare query");
        error_log($stmt_insert->error, 0);
        error_log($query_insert, 0);
        return;
    }
}

/**
 * Checks the database to see if a user with the given username and password exists
 * @param $username
 * @param $password
 * @param $connection
 * @return int
 */
function checkForExistingUser(
    $username,
    $password,
    $connection
)
{
    $query_check_user = "SELECT * FROM USERS WHERE username=? AND password=?";
    $stmt = $connection->stmt_init();
    if ($stmt->prepare($query_check_user)) {
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $value = $stmt->get_result();
            $stmt->close();
            if ($value->num_rows > 0) {
                $ret = $value->fetch_assoc();
                $_SESSION['username'] = $ret['username'];
                if ($ret['user_type'] === 'CLIENT') $_SESSION['user_level'] = 0;
                if ($ret['user_type'] === 'DEVELOPER') $_SESSION['user_level'] = 1;
                if ($ret['user_type'] === 'MANAGER') $_SESSION['user_level'] = 2;
                log_output("log.txt", $_SESSION['user_level'] . "\n");
                return 0; // User exists
            } else {
                return -1; // User does not exist
            }
        } else {
            echo("Could not execute statement.");
            return -1;
        }
    } else {
        echo("Failure to prepare query");
        return -1;
    }
}