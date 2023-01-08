<?php
session_start();
include "header.php";
include "checksession.php";
checkUser();

include "menu.php";


echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';
loginStatus(); //show the current login status
include "content.php";

echo '</div></div>';
include "footer.php";