<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Edit a booking</title>
    <!-- These are the jquery libraries. styling and javascript code -->
    <link rel="stylesheet"
        href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"
        integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="
        crossorigin="anonymous"></script>
    <script>
    // this is the jquery datepicker function. uses the class .datepicker 
    // min date sets the minimum the date can be to today.
    //  no min date as we may need to update a booking for yesyerday...
    // we should validate for checkout > checkin
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>
</head>

<body>

    <?php
    include "config.php"; //load in any variables
    include "cleaninput.php";

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    $error = 0;
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    };

    //retrieve the bookingid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid booking ID</h2>"; //simple error feedback
            exit;
        }
    }
    //the data was sent using a formtherefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {
        //validate incoming data - only the first field is done for you in this example - rest is up to you do

        //bookingID (sent via a form it is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid booking ID '; //append error message
            $id = 0;
        }
        //roomname
        $roomname = cleanInput($_POST['roomname']);
        //roomtype
        $roomtype = cleanInput($_POST['roomtype']);
        //beds
        $beds = cleanInput($_POST['beds']);
        //checkindate
        $checkindate = cleanInput($_POST['checkindate']);
        //checkoutdate
        $checkoutdate = cleanInput($_POST['checkoutdate']);
        // phone
        $phone = cleanInput($_POST['phone']);
        // extras
        $extras = cleanInput($_POST['extras']);
        // review
        $review = cleanInput($_POST['review']);

        //save the room data if the error flag is still clear and room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE booking, room SET room.roomname=?,roomtype=?,checkindate=?,checkoutdate=?,phone=?,extras=?,review=?,beds=? WHERE booking.bookingID=? and room.roomID = booking.roomID";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'sssssssii', $roomname, $roomtype, $checkindate, $checkoutdate, $phone, $extras, $review, $beds,  $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Room details updated.</h2>";
        } else {
            echo "<h2>$msg</h2>";
        }
    }
    //locate the booking to edit by using the roomID
    //we also include the room ID in our form for sending it back for saving the data
    $query = 'SELECT room.roomname, roomtype, beds, checkindate, checkoutdate, phone, extras, review FROM booking, room WHERE Booking.roomID = room.roomID AND bookingid=' . $id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);


    ?>

    <h1>Booking Details Update</h1>
    <h2><a href='listbookings.php'>[Return to the booking listing]</a><a href='index.php'>[Return to the main page]</a>
    </h2>
    <?php
        echo "<legend>booking id# $id
    </legend>";
        ?>

    <form method="POST"
        action="editbookings.php">
        <input type="hidden"
            name="id"
            value="<?php echo $id; ?>">
        <p>
            <label for="roomname">Room name: </label>
            <input type="text"
                id="roomname"
                name="roomname"
                minlength="5"
                maxlength="50"
                value="<?php echo $row['roomname']; ?>"
                required>
        </p>
        <p>
            <label for="roomtype">Room type: </label>
            <input type="radio"
                id="roomtype"
                name="roomtype"
                value="S"
                <?php echo $row['roomtype'] == 'S' ? 'Checked' : ''; ?>> Single
            <input type="radio"
                id="roomtype"
                name="roomtype"
                value="D"
                <?php echo $row['roomtype'] == 'D' ? 'Checked' : ''; ?>> Double
        </p>
        <p>
            <label for="beds">Sleeps (1-5): </label>
            <input type="number"
                id="beds"
                name="beds"
                min="1"
                max="5"
                value="1"
                value="<?php echo $row['beds']; ?>"
                required>
        </p>
        <p>
            <label for="checkindate">Check in date</label>
            <input class="datepicker"
                type="text"
                id="checkindate"
                name="checkindate"
                value="<?php echo $row['checkindate']; ?>"
                required>
        </p>
        <p>
            <label for="checkoutdate">Check out date</label>
            <input class="datepicker"
                type="text"
                id="checkoutdate"
                name="checkoutdate"
                value="<?php echo $row['checkoutdate']; ?>"
                required>
        </p>
        <p>
            <label for="phone">Contact Number (mobile): </label>
            <input type="tel"
                id="phone"
                name="phone"
                placeholder="##########"
                pattern="[0-9]{10}"
                value="<?php echo $row['phone']; ?>"
                required>
            <label>*</label>
        </p>
        <p>
            <label for="extras">Booking extras:</label>
            <textarea type="text"
                id="extras"
                name="extras"
                maxlength="1000"
                rows="5"
                cols="20"
                value="<?php echo $row['extras']; ?>">
            </textarea>
        </p>
        <p>
            <label for="review">Room review:</label>
            <textarea type="text"
                id="review"
                name="review"
                maxlength="1000"
                rows="5"
                cols="20"
                value="<?php echo $row['review']; ?>"></textarea>
        </p>
        <input type="submit"
            name="submit"
            value="Update">
        <input type="reset"
            value="Clear Form">
    </form>
    <?php
    } else {
        echo "<h2>Booking not found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($db_connection); //close the connection once done
    ?>
</body>

</html>