<?php
include "header.php";
include "menu.php";
include "checksession.php";
loginStatus(); //show the current login status

echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';


include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);


//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT bookingID,customerID FROM booking ORDER BY bookingID';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Current Bookings</h1>
<h2><a href='makeabooking.php'>[Make A Booking]</a><a href="index.php">[Return to main page]</a></h2>
<table border="1">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Customer ID</th>
            <th>Action</th>
        </tr>
    </thead>
    <?php

//makes sure we have bookings
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];	
	  echo '<tr><td>'.$row['bookingID'].'</td><td>'.$row['customerID'].'</td>';
	  echo     '<td><a href="viewbookings.php?id='.$id.'">[view]</a>';
	  
      //check if we have permission to modify data
      if (isAdmin()) {
        echo         '<a href="editbookings.php?id='.$id.'">[edit]</a>';
        echo         '<a href="deletebookings.php?id='.$id.'">[delete]</a></td>';
      }
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No bookings found!</h2>"; //suitable feedback
echo "</table>";
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done



echo '</div></div>';
require_once "footer.php";
?>