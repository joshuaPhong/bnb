<?php
// room search filter engine. Based on the customer search filter engine.
include "config.php";
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE)
    or die();

//  does the search query contain anything
$sq = $_GET['sq'];
$searchResult = '';
if (isset($sq) and !empty($sq)) {
    $sq = strtolower($sq);

    // prepar the query
    $query = "SELECT *
FROM room
";
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);
    // have we got a booking?
    if ($rowcount > 0) {
        $rows = []; // an empty array
        //append each row in the query result to our empty array until there are no more results                    
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        //take the array of our 1 or more customers and turn it into a JSON text
        $searchresult = json_encode($rows);
        //this line is cruicial for the browser to understand what data is being sent. so it knows its a json file
        header('Content-Type: text/json; charset=utf-8');
    } else echo "<tr><td colspan=3><h2>No Rooms Available!</h2></td></tr>";
} else echo "<tr><td colspan=3> <h2>Invalid search query</h2>";
mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done

echo  $searchresult;