<?php

// Include common PHP file
require_once "common.php";

// Initialize the session
session_start();

// Check if the user is already logged in, if no then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true); else redirect("index.php");

// Include config PHP file
require_once "config.php";

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['user_approve_btn'])) {
    // Prepare an update statement
    $sql = "UPDATE admin_users SET approved=1 WHERE user_id='" . $_POST['action_value'] . "'";    
    if ($record_management_system_db_conn->query($sql) === TRUE) {
        // Refresh the page.
        header("Refresh:0");
    }
    else {
        echo "Error updating record: " . $record_management_system_db_conn->error;
        $login_err = "Error updating record: " . $record_management_system_db_conn->error;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['back_index_btn'])) {
    redirect("index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="approve.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
    <ul class="navbar-nav abs-center-x">
        <li class="nav-item">
            <a class="nav-link" href="#">Approve Registrations</a>
        </li>
    </ul>

    <form class="form-inline" action="approve.php" method="post">
        <input class="btn btn-info my-2 my-sm-0" type="submit" name="back_index_btn" value="Back">  
    </form>
    <?php
        // Check if the user is already logged in, if yes then wite the hello mwssage to nav bar
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            echo '<span class="navbar-text">';
            echo 'Hello ' . $_SESSION["user_name"] . '!';
            echo '</span>';
        }
    ?>
    </nav>
    <table class="table table-dark table-hover">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">User Name</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <?php
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<th scope="col">Actions</th>';
            }
            ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            $users = [];

            // Prepare a select statement
            $get_users_sql = "SELECT * FROM admin_users WHERE approved=0";

            if ($result = $record_management_system_db_conn->query($get_users_sql)) {
                while ($data = $result->fetch_object()) {
                    $users[] = $data;
                }
            foreach ($users as $user) {
                echo "<tr>";
                echo '<th scope="row">' . $counter . '</th>';
                echo "<td>" . $user->user_id . "</td>";
                echo "<td>" . $user->user_name . "</td>";
                echo "<td>" . $user->first_name . "</td>";
                echo "<td>" . $user->last_name . "</td>";
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    echo '<form class="form-inline" action="approve.php" method="post">';
                    echo '<td>';
                    echo '<input type="submit" class="btn btn-danger" name="user_delete_btn" value=Delete>';
                    echo '<input type="submit" class="btn btn-success" name="user_approve_btn" value=Approve>';
                    echo '<input type="hidden" name="action_value" value=' . $user->user_id . '>';
                    echo '</td>';
                    echo '</form>';
                }
                echo" </tr>";
                $counter += 1;
            }
            }
            ?>
        </tbody>
    </table>
</body>
