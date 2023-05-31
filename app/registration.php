<?php
// Initialize the session
session_start();

// Include common PHP file
require_once "common.php";

// Check if the user is already logged in, if yes then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    redirect("index.php");
}
 
// Include config PHP file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $first_name = $last_name = $second_password = "";
$username_err = $password_err = $registration_err = $first_name_err = $last_name_err = $second_password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['cancel_btn'])) {
    redirect("index.php");
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['save_btn'])){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username!";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if first name is empty
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter first name!";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Check if last name is empty
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter last name!";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password!";
    } else{
        $password = trim($_POST["password"]);
    }

        // Check if second password is empty
    if(empty(trim($_POST["second_password"]))){
        $second_password_err = "Please enter password again!";
    } else{
        $second_password = trim($_POST["second_password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($first_name_err) && empty($last_name_err) && empty($second_password_err)){
        if (md5($password) != md5($second_password)) {
            $registration_err = "The password and re-password don't match!";
        }
        else{
            // Prepare an insert statement
            $sql = "INSERT INTO admin_users (user_name, first_name, last_name, password_hash, approved) VALUES";
            $sql .= " ('" . $username . "',";
            $sql .= " '" . $first_name . "',";
            $sql .= " '" . $last_name . "',";
            $sql .= " '" . md5($password) . "',";
            $sql .= " '" . 0 . "')";

            if ($record_management_system_db_conn->query($sql) === TRUE) {
                // Redirect to index page.
                redirect("index.php");
            }
            else {
                echo "Error create user: " . $record_management_system_db_conn->error;
                $registration_err = "Error create user: " . $record_management_system_db_conn->error;
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="registration.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="wrapper">
        <h2 class="registration_text">Registration</h2>
        <p class="please_fill_text">Please fill the registration form!</p>
        <p class="please_fill_text_important">A registred administrator has to approve your registration!</p>

        <?php 
        if(!empty($registration_err)){
            echo '<div class="alert alert-danger">' . $registration_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password Again</label>
                <input type="password" name="second_password" class="form-control <?php echo (!empty($second_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $second_password_err; ?></span>
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary" name="save_btn" value="Registration">
                    <input type="submit" class="btn btn-danger" name="cancel_btn" value="Cancel">
                </div>
            </div>
        </form>
    </div>
</body>
</html>