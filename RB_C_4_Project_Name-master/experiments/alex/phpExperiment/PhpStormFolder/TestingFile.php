

<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>

<?php
/**
 * Created by PhpStorm.
 * User: morale
 * Date: 9/8/17
 * Time: 6:13 PM
 */

echo "Testing changes";

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create connection
$connect = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";

if ($connect->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $connect->error;
}

$connect->close();
?>
</body>
</html>