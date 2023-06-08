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
$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['user_cancel_btn'])) {
    redirect("index.php");
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['user_login_btn'])){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username!";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password!";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT * FROM admin_users WHERE user_name='$username'";

        if ($result = $record_management_system_db_conn->query($sql)) {
            while ($data = $result->fetch_object()) {
                $users[] = $data;
            }
        }

        if (!isset($users) || empty($users)) {
            $login_err = "Invalid username.";
        }
        else {
            // echo "User is found in the DB.";
            foreach ($users as $user) {
                if ($username == $user->user_name) {
                    if ($user->approved != 1){
                        $login_err = "Your account is not approved by Admin!";
                    }
                    else {
                        if (md5($password) == $user->password_hash){
                            // echo "Password is matched";
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_name"] = $username;
                            redirect("index.php"); 
                        }
                        else {
                            $login_err = "Invalid password.";
                        }
                    }
                }
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="wrapper">
        <h2 class="login_text">Login</h2>
        <p class="please_fill_text">Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <div class="d-flex justify-content-center">
                    <input type="submit" class="btn btn-success" name="user_login_btn", value="Login">
                    <input type="submit" class="btn btn-danger" name="user_cancel_btn" value="Cancel">
                </div>
            </div>
        </form>
    </div>
</body>
</html>