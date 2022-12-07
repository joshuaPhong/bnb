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
    <link rel="stylesheet"
        href="/resources/demos/style.css">
    <!-- style for jquery selectmenu -->
    <style>
    fieldset {
        border: 1;
    }

    label {
        display: block;
        margin: 30px 0 0 0;
    }

    .overflow {
        height: 200px;
    }
    </style>
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
            minDate: 0
        });
    });
    </script>
    <script>
    $(function() {
        $("#room").selectmenu();
    });
    </script>
</head>
<!-- this page is for the customer to make a booking. it is grouped into two parts. make a booking and a search for room availability -->

<body>

    <?php
    include "cleaninput.php"; // cleans up the user input for posting

    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);


    //check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    }

    //prepare a query and send it to the server
    $query = 'SELECT roomID,roomname,roomtype, beds FROM room';
    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);

    ?>
    <!-- page heading and links to other pages -->
    <h1>Make a Booking</h1>
    <p><a href='listbookings.php'>[Return to the Bookings listing]</a><a href="index.php">[Return to Main
            Page]</a>
    </p>
    <!-- the firat part of the page is a fortm for booking -->
    <fieldset>
        <legend>Customer ID#</legend>
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
                            $id = $row['roomID'];
                            echo "<option>" . $row['roomname'] . ", " . $row['roomtype'] . ", " . $row['beds'] . "</option>";
                        }
                    }

                    mysqli_free_result($result); //free any memory used by the query
                    mysqli_close($DBC); //close the connection once done
                    ?>

                </select>

                <label>*</label>
            </p>
            <!-- a date picker for the customer to select thier checkin date. this is a required field/ client side data validation -->
            <!-- there is no validation to make sure the date is within a suitable range, i.e. not the past -->
            <p>
                <label for="checkin">Checkin date:</label>
                <input class="datepicker"
                    type="text"
                    id="checkin"
                    name="checkin"
                    required>
                <label>*</label>
            </p>
            <!-- a date picker for the check out date, is required* -->
            <p>
                <label for="checkout">Checkout Date: </label>
                <input class="datepicker"
                    type="text"
                    id="checkout"
                    name="checkout"
                    required>
                <label>*</label>
            </p>
            <!-- telephone input. with a place hoder to illistate the required pattern. this input is optional -->
            <p>
                <label for="phone">Contact Number (mobile): </label>
                <input type="tel"
                    id="phone"
                    name="phone"
                    placeholder="###-###-####"
                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
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
    <form>
        <p>
            <label for="startdate">Start date:</label>
            <input type="date"
                id="startdate"
                name="startdate"
                required>
            <label>*</label>
            <label for="enddate">End Date: </label>
            <input type="date"
                id="enddate"
                name="enddate"
                required>
            <label>*</label>
            <input type="submit"
                name="submit"
                value="Search availability">
        </p>
    </form>
    <!-- a table to display the results of the search. The data displayed is indicitive only/ for display puposes as the pages are not connected to a DB at present -->
    <table border="1">
        <thead>
            <tr>
                <th>Room #</th>
                <th>Room name</th>
                <th>Room type</th>
                <th>Beds</th>
            </tr>
        </thead>
        <tr>
            <td>1</td>
            <td>Kellie</td>
            <td>S</td>
            <td>5</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Herman</td>
            <td>D</td>
            <td>5</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Scarlett</td>
            <td>D</td>
            <td>2</td>
        <tr>
            <td>4</td>
            <td>Jelani</td>
            <td>S</td>
            <td>2</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Sonya</td>
            <td>S</td>
            <td>5</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Miranda</td>
            <td>S</td>
            <td>4</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Helen</td>
            <td>S</td>
            <td>2</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Octavia</td>
            <td>D</td>
            <td>3</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Gretchen</td>
            <td>D</td>
            <td>3</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Bernard</td>
            <td>S</td>
            <td>5</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Dacey</td>
            <td>D</td>
            <td>2</td>
        </tr>
        <tr>
            <td>12</td>
            <td>Preston</td>
            <td>D</td>
            <td>2</td>
        </tr>
        <tr>
            <td>13</td>
            <td>Dane</td>
            <td>S</td>
            <td>4</td>
        </tr>
        <tr>
            <td>14</td>
            <td>Cole</td>
            <td>S</td>
            <td>1</td>
        </tr>
    </table>

</body>

</html>