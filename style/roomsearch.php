<?php
// room search filter engine. Based on the customer search filter engine.
include "config.php";
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE)
or die();

//  does the search query contain anything
$sq = $_GET['sq'];
$searchResult = '';
if(isset($sq) and !empty($sq)){
    $sq = strtolower($sq);

}
