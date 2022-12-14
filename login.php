<?php
// access the database constants
include "config.php";
// connect to the database using the constants
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
// if the login form has been filled in 
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //prepare a query and send it to the server 
    $stmt = mysqli_stmt_init($db_connection);
    mysqli_stmt_prepare($stmt, "SELECT memberID, password FROM member WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerID, $hashpassword);
    mysqli_stmt_fetch($stmt);
    // this is where the password is checked 
    if (!$customerID) {
        echo '<p class="error">Unable to find member with email!' . $email . '</p>';
    } else {
        if (password_verify($password, $hashpassword)) {
            echo '<p>Congratulations, you are logged in!</p>';
        } else {
            echo '<p>Username/password combination is wrong!</p>';
        }
    }
}

mysqli_close($db_connection);
?>

<h1>Login Page</h1>
<a href="index.php">Main menu</a>
<form method="POST"
    action="login.php">


    <label for="username">Username: </label>
    <input type="email"
        name="email"
        id="email"
        size="30"
        required>
    <br>
    <label for="password">Password</label>
    <input type="password"
        name="password"
        id="password"
        size="15"
        min="10"
        max="30"
        required>
    <br>
    <input type="submit"
        name="submit"
        value="Login">

</form>