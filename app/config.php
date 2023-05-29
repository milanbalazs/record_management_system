<?php
// The MySQL service named in the docker-compose.yml.
$host = getenv('MYSQL_HOST');;

// The root user is used for the connection.
// TODO: Probably it's more secure to use a non-root user.
$user = 'root';

// Password fo the used DB user.
$pass = getenv('MYSQL_ROOT_PASSWORD');

// Name of the used database.
$record_management_system_db = 'record_management_system';

$record_management_system_db_conn = new mysqli($host, $user, $pass, $record_management_system_db);

// Check connection
if($record_management_system_db_conn === false){
    die("ERROR: Could not connect: " . mysqli_connect_error());
}
?>
