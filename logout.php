<?php

include "header.php";
include "checksession.php";
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Logout')) {

logout();


}

include "menu.php";


echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';
loginStatus(); //show the current login status

echo "<h2>Are you sure you want to logout?</h2>";
?>
<form method="post"
    action="logout.php">
    <input type="submit"
        name="submit"
        value="Logout">
</form>
<?php


echo '</div></div>';
include "footer.php";