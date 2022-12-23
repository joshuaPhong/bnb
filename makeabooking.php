<?php
// start a session. for the interpage data
session_start();
include "config.php"; //load in any variables
include "cleaninput.php"; // for use in data validation befor sending to the database
// make the db connection
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
};

?>

<head>
    <!-- These are the jquery libraries. styling and javascript code -->
    <link rel="stylesheet"
        href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"
        integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="
        crossorigin="anonymous"></script>
    <script>
    // this is the jquery datepicker function. uses the class .datepicker 
    // min date sets the minimum the date can be to today.
    // no booking in the past \
    // we should validate for checkout > checkin
    $(function() {
        $(".datepicker").datepicker({
            minDate: 0,
            // same format as sql
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>
</head>
<?php
include "checksession.php";
// html metadata
include "header.php";
include "menu.php";
loginStatus(); //show the current login status

echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';
?>
<h1>Make a Booking</h1>
<h2>
    <ul>
        <a href="./listbookings.php">[Return to Bookings List]</a>
        <a href="./index.php">[Return to Main Menu]</a>
    </ul>
</h2>
<fieldset>
    <form method="$_POST"
        action="./makeabooking.php">
        <p>
            <label for="room">Please select a room (name, type, beds):</label>
            <select name="room"
                id="room">
                <?php
                //prepare a query and send it to the server
                $query = 'SELECT roomID,roomname,roomtype, beds FROM room';
                $result = mysqli_query($db_connection, $query);
                $rowcount = mysqli_num_rows($result);
                //makes sure we have rooms
                if ($rowcount > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $roomID = $row['roomID'];
                        echo "<option>" . $row['roomname'] . ", " . $row['roomtype'] . ", " . $row['beds'] . "</option>";
                    }
                }

                mysqli_free_result($result); //free any memory used by the query
                mysqli_close($db_connection); //close the connection once done
                ?>

            </select>

            <label id="req">*</label>
        </p>
        <p>
            <label for="checkindate">Check In Date:</label>
            <input type="text"
                class="datepicker"
                id="checkindate"
                name="checkindate"
                required>
            <label id="req">*</label>
        </p>
        <p>
            <label for="checkoutdate">Check Out Date:</label>
            <input type="text"
                class="datepicker"
                id="checkoutdate"
                name="checkoutdate"
                required>
            <label id="req">*</label>
        </p>
        <p>
            <label for="phone">Mobile Phone Number:</label>
            <input type="tel"
                id="phone"
                name="phone"
                placeholder="##########"
                pattern="[0-9]{10}"
                required>
            <label id="req">*</label>
        </p>
        <p>
            <label for="extras">Booking Extras:</label>
            <textarea type="text"
                id="extras"
                name="extras"
                maxlength="1000"
                rows="5"
                cols="20"></textarea>
        </p>
        <p>
            <!-- a button to submit form data to the DB -->
            <input type="submit"
                name="submit"
                value="Add">
            <!-- a button to clear the form and reset it to the defaults -->
            <input type="reset"
                value="Clear Form">
        </p>
    </form>
</fieldset>


<?php

echo '</div></div>';
include "footer.php";
?>