<?php
session_start();

// //overrides for development purposes only - comment this out when testing the login
// $_SESSION['loggedin'] = false;
// $_SESSION['userid'] = 0; //this is the ID for the admin user  
// $_SESSION['username'] = '';
// $_SESSION['role'] = 0;
// //end of overrides

function isAdmin()
{
    if (($_SESSION['loggedin'] == true) and ($_SESSION['role'] == 9))
        return TRUE;
    else
        return FALSE;
}

function isMember()
{
    if (($_SESSION['loggedin']  = true) and ($_SESSION['role']  == 1))
        return true;
    else
        return false;
}

//function to check if the user is logged else send to the login page 
function checkUser()
{
    return true;
    $_SESSION['URI'] = '';
    if ($_SESSION['loggedin'] == true)
        return TRUE;
    else {
        $_SESSION['URI'] = 'http://localhost' . $_SERVER['REQUEST_URI']; //save current url for redirect     
        header('Location: http://joshuawebapp.unaux.com/bnb/login.php', true, 303);
    }
}

//just to show we are logged in
function loginStatus()
{

    if ($_SESSION['loggedin']) {
        $username = $_SESSION['username'];
        echo "<h2>Logged in as $username</h2>";
    }
    elseif (!$_SESSION['loggedin'])
         echo "<h2>Logged out</h2>";
}

//log a user in
function login($customerID, $username)
{
    //simple redirect if a user tries to access a page they have not logged in to
    if ($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI']))
        $uri = $_SESSION['URI'];
    else {
        $_SESSION['URI'] =  'http://localhost/bnb/listcustomers.php';
        $uri = $_SESSION['URI'];
    }

    $_SESSION['loggedin'] = 1;
    $_SESSION['customerid'] = $customerID;
    $_SESSION['username'] = $username;
    $_SESSION['URI'] = '';
    header('Location: ' . $uri, true, 303);
}

//simple logout function
function logout()
{
    session_start();

   session_destroy();
    $_SESSION = array();
       header('Location: http://localhost/bnb/index.php', true, 303);
        exit();

    }

