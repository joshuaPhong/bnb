<?php
include "header.php";
include "menu.php";
include "checksession.php";


echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';
loginStatus(); //show the current login status
checkUser();
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE); // connect to the database


//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT * FROM booking, room, customer WHERE booking.roomID = room.roomID AND booking.customerID = customer.customerID ORDER BY bookingID';
$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);
?>
<!-- page heading and anchors -->
<h1>Current Bookings</h1>
<h2><a href='makeabooking.php'>[Make A Booking]</a><a href="index.php">[Return to main page]</a></h2>
<!-- create a table  -->
<table border="1">
    <thead>
        <tr>
            <th>Booking (room, dates)</th>
            <th>Customer </th>
            <th>Action</th>
        </tr>
    </thead>
    <?php

    //makes sure we have bookings and then loop through the bookings with our $result atrray
    if ($rowcount > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['bookingID'];
            echo '<tr><td>' . $row['roomname'] . ", " . $row['checkindate'] . ", " . $row['checkoutdate'] . '</td><td>' . $row['lastname'] . ", " . $row['firstname'] . '</td>';
            echo     '<td><a href="viewbookings.php?id=' . $id . '">[view]</a>';

            //check if we have permission to modify data
            if (isAdmin()) {
                echo         '<a href="editbookings.php?id=' . $id . '">[edit]</a>';
                echo         '<a href="deletebookings.php?id=' . $id . '">[delete]</a>';
            }
            echo '</tr>' . PHP_EOL;
        }
    } else echo "<h2>No bookings found!</h2>"; //suitable feedback
    echo "</table>";
    mysqli_free_result($result); //free any memory used by the query
    mysqli_close($DBC); //close the connection once done



    echo '</div></div>';
    require_once "footer.php";
    ?>