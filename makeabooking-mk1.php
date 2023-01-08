<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Make a Booking</title>
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
    <script>
    $(function() {
        $("#room").selectmenu();
    });
    </script>
    <!-- script for the xmlhttp request. customer search -->
    <script>
    function searchResult(searchstr) {
        if (searchstr.length == 0) {
            return;
        }
        // new object
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var mbrs = JSON.parse(this.responseText); // turn json into javascipt
                console.log(JSON.parse(this.responseText));
                var tbl = document.getElementById("tblrooms"); // find the table in the html
                // clear the table rows from earlier searches
                var rowCount = tbl.rows.length;
                for (var i = 1; i < rowCount; i++) {
                    // delete row
                    tbl.deleteRow(1);
                }

                // populate the table. mbrs.length is the size of the array
                for (var i = 0; i < mbrs.length; i++) {
                    var rid = mbrs[i]['roomID'];
                    var rn = mbrs[i]['roomname'];
                    var rt = mbrs[i]['roomtype'];
                    var b = mbrs[i]['beds'];

                    //concatenate our actions urls into a single string
                    var urls = '<a href="viewcustomer.php?id=' + mbrid + '">[view]</a>';
                    urls += '<a href="editcustomer.php?id=' + mbrid + '">[edit]</a>';
                    urls += '<a href="deletecustomer.php?id=' + mbrid + '">[delete]</a>';

                    //create the table. a table row with four cells.
                    tr = tbl.insertRow(-1);
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = rid;
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = rn;
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = rt;
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = b;
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = urls; //action URLS  
                }
            }
        }
        //  open connection
        xmlhttp.open("GET", "roomsearch.php?sq=" + searchstr, true);

        // send request to the server 
        xmlhttp.send();


    }
    xmlhttp.onload = function() {
        alert('response loaded');
    }
    xmlhttp.onerror = function() {
        alert("Connection error");
    }

    xmlhttp.onprogress = function(event) {
        alert("in progress");
    }
    </script>
    <!-- this page is for the customer to make a booking. it is grouped into two parts. make a booking and a search for room availability -->
</head>

<body>
    <?php

    include "config.php"; //load in any variables
    include "cleaninput.php";
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    if (mysqli_connect_errno()) {
        echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit;
    };

    //the data was sent using a form therefore you use the $_POST instead of $_GET
    //check if you are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
        // validate incoming data - only the first field is done for you in this example - rest is up to you to do
        $error = 0; // set an error flag
        $msg = 'Error: ';
        // firstname
        if (isset($_POST['checkindate']) and !empty($_POST['checkindate'])) {
            $fn = cleaninput($_POST['checkindate']);
        } else {
            $error++; // increment the error flag
            $msg .= 'Invalid check in date '; // append error message
            $checkin = ' ';
        }
        // lastname
        if (isset($_POST['checkoutdate']) and !empty($_POST['checkoutdate'])) {
            $fn = cleaninput($_POST['checkoutdate']);
        } else {
            $error++; // increment the error flag
            $msg .= 'Invalid check in date '; // append error message
            $checkout = ' ';
        }
        // email
        if (isset($_POST['phone']) and !empty($_POST['phone'])) {
            $fn = cleaninput($_POST['phone']);
        } else {
            $error++; // increment the error flag
            $msg .= 'Invalid phone number '; // append error message
            $phone = ' ';
        }
        // username
        if (isset($_POST['extras']) and is_string($_POST['extras'])) {
            $fn = cleaninput($_POST['extras']);
        } else {
            $error++; // increment the error flag
            $msg .= 'Invalid extras '; // append error message
            $extras = ' ';
        }
        $roomID = cleanInput('roomID');

        //role - not in the form so declared here
        $role = 1;

        // save the member data if the error flag is still clear
        if ($error == 0) {
            $query = "INSERT INTO booking (checkindate, checkoutdate, extras, phone, booking.customerID, roomID) VALUES (?,?,?,?,?,?)";


            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'ssssss', $checkin, $checkout, $extras, $phone, $roomID, $customerID);


            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>booking saved</h2>";
        } else {
            echo "<h2>$msg</h2>";
        }
    }

    ?>

    <p>
        <?php

        //prepare a query and send it to the server
        $query = 'SELECT roomID,roomname,roomtype, beds FROM room';
        $result = mysqli_query($db_connection, $query);
        $rowcount = mysqli_num_rows($result);

        ?>
        <!-- page heading and links to other pages -->
    <h1>Make a Booking</h1>
    <p><a href='listbookings.php'>[Return to the Bookings listing]</a><a href="index.php">[Return to Main
            Page]</a>
    </p>
    <!-- the firat part of the page is a fortm for booking -->
    <fieldset>

        <!-- not connected yet -->
        <form method="POST"
            action="makeabooking.php">
            <!-- a drop down menu for the customer to select their choice of room with two illistrative peices of data -->
            <p>


                <label for="room">Please select a room (name, type, beds):</label>
                <select name="room"
                    id="room">
                    <?php
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

                <label>*</label>
            </p>
            <!-- a date picker for the customer to select thier checkin date. this is a required field/ client side data validation -->
            <!-- there is no validation to make sure the date is within a suitable range, i.e. not the past -->
            <p>
                <label for="checkindate">Checkin date:</label>
                <input class="datepicker"
                    type="text"
                    id="checkindate"
                    name="checkindate"
                    required>
                <label>*</label>
            </p>
            <!-- a date picker for the check out date, is required* -->
            <p>
                <label for="checkoutdate">Checkout Date: </label>
                <input class="datepicker"
                    type="text"
                    id="checkoutdate"
                    name="checkoutdate"
                    required>
                <label>*</label>
            </p>
            <!-- telephone input. with a place hoder to illistate the required pattern. this input is optional -->
            <p>
                <label for="phone">Contact Number (mobile): </label>
                <input type="tel"
                    id="phone"
                    name="phone"
                    placeholder="##########"
                    pattern="[0-9]{10}"
                    required>
            </p>
            <!-- a tect area for the customer to inform staff of any special requirements that they might have. it has no minlength, is not a required feild but there is a limit on how much text can be entered. about 200 words  -->
            <p>
                <label for="extras">Booking extras:</label>
                <textarea type="text"
                    id="extras"
                    name="extras"
                    maxlength="1000"
                    rows="5"
                    cols="20"></textarea>
            </p>
            <!-- buttons and link.  -->
            <p>
                <!-- a button to submit form data to the DB -->
                <input type="submit"
                    name="submit"
                    value="Add">
                <!-- a button to clear the form and reset it to the defaults -->
                <input type="reset"
                    value="Clear Form">
                <!-- a link to the home pages menu, if the user changes thier mind -->
                <a href="index.html">[Cancel]</a>
            </p>
        </form>
    </fieldset>
    <!--  part two of the page allows the user to search the site for rooms that are available for booking. it contains a form to set the search parameters and a table to display the reults -->
    <h2>Search for room availability</h2>
    <!-- the form. two date pickers to set the range of the search and a button to submit the results. both date pickers are required, this will set the data range provide to the table -->
    <fieldset>
        <form>
            <p>
                <label for="startdate">Start date:</label>
                <input class="datepicker"
                    type="text"
                    id="startdate"
                    name="startdate"
                    required>
                <label>*</label>
                <label for="enddate">End Date: </label>
                <input class="datepicker"
                    type="text"
                    id="enddate"
                    name="enddate"
                    required>
                <label>*</label>
                <input type="submit"
                    name="submit"
                    value="Search availability"
                    onclick="searchResult(this.value)">
            </p>
        </form>
        <!-- a table to display the results of the search. -->
        <table id="tblrooms"
            border="1">
            <thead>
                <tr>
                    <th>Room #</th>
                    <th>Room name</th>
                    <th>Room type</th>
                    <th>Beds</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </fieldset>



</body>

</html>