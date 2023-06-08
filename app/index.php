<?php

// Include common PHP file
require_once "common.php";
// Include config PHP file
require_once "config.php";

// Initialize the session
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['login_btn'])) {
    redirect("login.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['reg_btn'])) {
    redirect("registration.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['logout_btn'])) {
    redirect("logout.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['apr_btn'])) {
    redirect("approve.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['delete_btn'])) {
    $sql = "DELETE FROM cars WHERE car_id='" . $_POST['action_value'] . "'";
    if ($record_management_system_db_conn->query($sql) === TRUE) {
        // Refresh the page.
        header("Refresh:0");
    }
    else {
        echo "Error deleting record: " . $record_management_system_db_conn->error;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['add_btn'])) {
    redirect("add_modify.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['modify_btn'])) {
    header("Location:add_modify.php?record_id=" . $_POST['action_value']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
    <ul class="navbar-nav abs-center-x">
        <li class="nav-item">
            <a class="nav-link" href="#">Record Management System</a>
        </li>
    </ul>

    <form class="form-inline" action="index.php" method="post">     
        <?php
            // Check if the user is already logged in, if yes then create the following buttons
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<input class="btn btn-danger" type="submit" name="logout_btn" value="Logout">';
                echo '<input class="btn btn-success" type="submit" name="add_btn" value="Add">';
                echo '<input class="btn btn-info" type="submit" name="apr_btn" value="Approvals">';
            }
            else {
                echo '<input class="btn btn-success my-2 my-sm-0" type="submit" name="login_btn" value="Login">';
                echo '<input class="btn btn-info my-2 my-sm-0" type="submit" name="reg_btn" value="Registration">';
            }
        ?>
    </form>
    <?php
        // Check if the user is already logged in, if yes then write the hello message to nav bar
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
            <th scope="col">Type</th>
            <th scope="col">Fuel</th>
            <th scope="col">Year</th>
            <th scope="col">Seats</th>
            <th scope="col">Price</th>
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
            $cars = [];

            // Prepare a select statement
            $get_cars_sql = "SELECT * FROM cars";

            if ($result = $record_management_system_db_conn->query($get_cars_sql)) {
                while ($data = $result->fetch_object()) {
                    $cars[] = $data;
                }
            foreach ($cars as $car) {
                echo "<tr>";
                echo '<th scope="row">' . $counter . '</th>';
                echo "<td>" . $car->car_id . "</td>";
                echo "<td>" . $car->car_type . "</td>";
                echo "<td>" . $car->car_fuel . "</td>";
                echo "<td>" . $car->car_year . "</td>";
                echo "<td>" . $car->car_seats . "</td>";
                echo "<td>" . $car->car_price . "</td>";
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    echo '<form class="form-inline" action="index.php" method="post">';
                    echo '<td>';
                    echo '<input type="submit" class="btn btn-danger" name="delete_btn" value=Delete>';
                    echo '<input type="submit" class="btn btn-warning" name="modify_btn" value=Modify>';
                    echo '<input type="hidden" name="action_value" value=' . $car->car_id . '>';
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
