<?php
// Initialize the session
session_start();

// Include common PHP file
require_once "common.php";

// Check if the user is already logged in, if no then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true); else redirect("index.php");

// Include config PHP file
require_once "config.php";

// Init values of variables
$header_text="Add new record";
$login_err = "";
$car_type = "Type";
$car_fuel = "Fuel";
$car_seats = $car_price = "0";
$car_year = date('Y-m-d');
$modify_action = FALSE;

// Redirect back to index.php in case of Cancel button pressing.
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['mod_save_btn'])) {

    // Prepare an update statement
    $sql = "UPDATE cars SET";
    $sql .= " car_type='" . $_POST['type'] . "',";
    $sql .= " car_fuel='" . $_POST['fuel'] . "',";
    $sql .= " car_seats='" . $_POST['seats'] . "',";
    $sql .= " car_year='" . $_POST['year'] . "',";
    $sql .= " car_price='" . $_POST['price'] . "'";
    $sql .= " WHERE car_id='" . $_POST['action_value'] . "'";
        
    if ($record_management_system_db_conn->query($sql) === TRUE) {
        // Redirect to index page.
        redirect("index.php");
    }
    else {
        echo "Error updating record: " . $record_management_system_db_conn->error;
        $login_err = "Error updating record: " . $record_management_system_db_conn->error;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['cancel_btn'])) {
    redirect("index.php");
}

if(isset($_GET['record_id'])){
    $record_id = $_GET['record_id'];
    $header_text = "Modify - " . $record_id . ". record";
    $modify_action = TRUE;
    // Prepare a select statement
    $sql = "SELECT * FROM cars WHERE car_id='$record_id'";
    
    if ($result = $record_management_system_db_conn->query($sql)) {
        while ($data = $result->fetch_object()) {
            $cars[] = $data;
        }
    }

    if (!isset($cars) || empty($cars)) {
        $login_err = "Invalid Record ID.";
    }

    foreach ($cars as $car) {
        $car_type = $car->car_type;
        $car_fuel = $car->car_fuel;
        $car_seats = $car->car_seats;
        $car_price = $car->car_price;
        $car_year = $car->car_year;
    }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="add_modify.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="wrapper">
        <h2 class="header_text"><?php echo $header_text;?></h2>
        <p class="please_fill_text">Please fill the form</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="<?php echo $car_type;?>">
                <span class="invalid-feedback"><?php echo "Invalid Type"; ?></span>
            </div>    
            <div class="form-group">
                <label>Fuel</label>
                <input type="text" name="fuel" class="form-control" value="<?php echo $car_fuel;?>">
                <span class="invalid-feedback"><?php echo "Invalid Fuel"; ?></span>
            </div>
            <div class="form-group">
                <label>Year</label>
                <input type="text" name="year" class="form-control" value="<?php echo $car_year;?>">
                <span class="invalid-feedback"><?php echo "Invalid Year"; ?></span>
            </div>
            <div class="form-group">
                <label>Seats</label>
                <input type="text" name="seats" class="form-control" value="<?php echo $car_seats;?>">
                <span class="invalid-feedback"><?php echo "Invalid Seats"; ?></span>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" class="form-control" value="<?php echo $car_price;?>">
                <span class="invalid-feedback"><?php echo "Invalid Price"; ?></span>
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-center">
                    <?php
                        if($modify_action === TRUE){
                            echo '<input type="submit" class="btn btn-success" name="mod_save_btn" value="Save">';
                            echo '<input type="hidden" name="action_value" value=' . $record_id . '>';
                        }
                        else{
                            echo '<input type="submit" class="btn btn-success" name="save_btn" value="Save">';
                        }
                    ?>
                    <input type="submit" class="btn btn-danger" name="cancel_btn" value="Cancel">
                </div>
            </div>
        </form>
    </div>
</body>
</html>