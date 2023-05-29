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

$sql_query = 'SELECT * FROM admin_users';

if ($result = $record_management_system_db_conn->query($sql_query)) {
    while ($data = $result->fetch_object()) {
        $sdmin_users[] = $data;
    }
}

foreach ($sdmin_users as $user) {
    echo "<br>";
    echo $user->user_id . " " . $user->user_name . " " . $user->first_name . " " . $user->last_name; // . " " . $user->password_hash;
    echo "<br>";
}
?>