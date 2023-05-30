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

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['logout_btn'])) {
    redirect("logout.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
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
            // Check if the user is already logged in, if yes then redirect him to index page
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<input class="btn btn-danger my-2 my-sm-0" type="submit" name="logout_btn" value="Logout" />';
            }
            else {
                echo '<input class="btn btn-success my-2 my-sm-0" type="submit" name="login_btn" value="Login" />';
            }
        ?>
    </form>
    <?php
        // Check if the user is already logged in, if yes then redirect him to index page
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            echo '<span class="navbar-text">';
            echo 'Hello ' . $_SESSION["user_name"] . '!';
            echo '</span>';
        }
    ?>
    </nav>
    <table class="table table-dark">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Type</th>
            <th scope="col">Fuel</th>
            <th scope="col">Year</th>
            <th scope="col">Seats</th>
            <th scope="col">Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            $cars = [];

            // Prepare a select statement
            $sql = "SELECT * FROM cars";

            if ($result = $record_management_system_db_conn->query($sql)) {
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
                echo" </tr>";
                $counter += 1;
            }
            }
            ?>
        </tbody>
    </table>
</body>
