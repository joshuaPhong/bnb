<?php
session_start();
include "header.php";
include "checksession.php";

include "menu.php";

echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';

// access the database constants
include "config.php";
// connect to the database using the constants
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
// if the login form has been filled in 
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashpassword = password_hash($password, PASSWORD_DEFAULT);

    //prepare a query and send it to the server 
    $stmt = mysqli_stmt_init($db_connection);
    mysqli_stmt_prepare($stmt, "SELECT customerID, password, role FROM customer WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerID, $hashpassword, $role);
    mysqli_stmt_fetch($stmt);
    // this is where the password is checked 
    if (!$customerID) {
        echo '<p class="error">Unable to find member with email!' . $username . '</p>';
    } else {
        if (password_verify($password, $hashpassword)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['customerID'] = $customerID;
        } else {
            echo '<p>Username/password combination is wrong!</p>';
        }
    }

    echo '<h1><a href="index.php">[Return to the main menu]</a></h1>';
}
if (isset($_POST['logout'])) {
    logout();
    loginStatus(); //show the current login status
    exit();
}


mysqli_close($db_connection);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body>
    <h1>Login Page</h1>
    <?php
    if ($_SESSION['loggedin'] == 1) {
        loginStatus();
    } else
        echo " <h2>You are not logged in. Please log in</h2>
    "

    ?>
    <form method="POST" action="login.php">


        <label for="username">Email Address: </label>
        <input type="email" name="username" id="username" size="30" required>
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" size="15" min="10" max="30" required>
        <br>
        <br>
        <input type="submit" name="submit" value="Login">

    </form>
    <br>
    <form action="login.php" method="post">
        <input type="submit" name="logout" value="logout">
    </form>
    <br><br>
    <h2>If you are not a member, please register</h2>
    <a href="registercustomer.php">[Register]</a>
</body>

</html>



<?php
echo '</div></div>';
require_once "footer.php";
?>