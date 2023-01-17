<?php
// start a session. for the interpage data
session_start();

include "config.php"; //load in any variables
include "cleaninput.php"; // for use in data validation befor sending to the database
include "checksession.php";
checkUser();
if ((!isMember()) and (!isAdmin())) {
    header('Location: http://localhost/bnb/login.php');
    exit();
}
// make the db connection
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
?>


<head>
    <!-- <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <!-- These are the jquery libraries. styling and javascript code -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
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
        $(document).ready(function() {

            $('.dateFilter').datepicker({
                dateFormat: "yy-mm-dd"
            });

            $('#btn_search').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    $.ajax({
                        url: "action.php",
                        method: "POST",
                        data: {
                            from_date: from_date,
                            to_date: to_date
                        },
                        success: function(data) {
                            $('#purchase_order').html(data);
                        }
                    });
                } else {
                    alert("Please Select the Date");
                }
            });
        });
    </script>
</head>
<?php

// html metadata
include "header.php";
include "menu.php";


echo '<div id="site_content">';
include "sidebar.php";

if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {

    $error = 0; // set an error flag
    $msg = 'Error: ';

    //checkindate
    if (isset($_POST['checkindate']) and !empty($_POST['checkindate'])) {
        $checkin = cleanInput($_POST['checkindate']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid Date '; //append error message
        $id = 0;
    }

    //checkoutdate
    if (
        isset($_POST['checkoutdate']) and !empty($_POST['checkoutdate'])
    ) {
        $checkout = cleanInput($_POST['checkoutdate']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid Date '; //append error message
        $id = 0;
    }

    // phone
    if (
        isset($_POST['phone']) and !empty($_POST['phone'])
    ) {
        $phone = cleanInput($_POST['phone']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid Date '; //append error message
        $id = 0;
    }

    // extras
    if (
        isset($_POST['extras']) and is_string($_POST['extras'])
    ) {
        $extras = cleanInput($_POST['extras']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid input '; //append error message
        $id = 0;
    }

    $customerID = $_SESSION['customerID'];
    $roomID = filter_input(INPUT_POST, "roomID", FILTER_VALIDATE_INT);
    $username = $_SESSION['username'];


    // save the member data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO booking (checkindate, checkoutdate, extras, phone, customerID, roomID) VALUES (?,?,?,?,?,?)";


        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param(
            $stmt,
            'ssssii',
            $checkin,
            $checkout,
            $extras,
            $phone,
            $customerID,
            $roomID
        );

        echo "<h2>booking saved</h2>";
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "<h2>$msg</h2>";
    }
}



echo '<div id="content">';
loginStatus(); //show the current login status
// login($id, $username);
?>
<h1>Make a Booking</h1>
<h2>
    <ul>
        <a href="./listbookings.php">[Return to Bookings List]</a>
        <a href="./index.php">[Return to Main Menu]</a>
    </ul>
</h2>
<fieldset>
    <form method="POST" action="makeabooking.php">
        <p>
            <label for="roomID">Please select a room (name, type, beds):</label>
            <select name="roomID" id="roomID">
                <?php

                //prepare a query and send it to the server
                $query = 'SELECT roomID,roomname,roomtype, beds FROM room';
                $result = mysqli_query($db_connection, $query);

                //            $roomID = ['roomID'];
                $rowcount = mysqli_num_rows($result);


                //makes sure we have rooms
                if ($rowcount > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

                        echo "<option value='$row[roomID]'>"  . $row['roomname'] . ", " . $row['roomtype'] . ", " . $row['beds'] . "</option>";
                    }
                }
                mysqli_free_result($result); //free any memory used by the query


                ?>

            </select>

            <label id="req">*</label>
        </p>


        <label for="checkindate">Check In Date:</label>
        <input type="text" class="datepicker" id="checkindate" name="checkindate" required>
        <label id="req">*</label>
        </p>
        <p>
            <label for="checkoutdate">Check Out Date:</label>
            <input type="text" class="datepicker" id="checkoutdate" name="checkoutdate" required>
            <label id="req">*</label>
        </p>
        <p>
            <label for="phone">Mobile Phone Number:</label>
            <input type="tel" id="phone" name="phone" placeholder="##########" pattern="[0-9]{10}" required>
            <label id="req">*</label>
        </p>
        <p>
            <label for="extras">Booking Extras:</label>
            <textarea type="text" id="extras" name="extras" maxlength="1000" rows="5" cols="20"></textarea>
        </p>
        <p>
            <!-- a button to submit form data to the DB -->
            <input type="submit" name="submit" value="Add">
            <!-- a button to clear the form and reset it to the defaults -->
            <input type="reset" value="Clear Form">
        </p>
    </form>
</fieldset>
<?php
$dbc = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
$query = "SELECT * FROM room ORDER BY roomID";
$result = mysqli_query($dbc, $query);
?>
<br>
<br>
<div class="container">
    </br>
    <div class="row">
        <div class="col-md-2">
            <input type="text" name="from_date" id="from_date" class="form-control dateFilter" placeholder="From Date" />
        </div>
        <div class="col-md-2">
            <input type="text" name="to_date" id="to_date" class="form-control dateFilter" placeholder="To Date" />
        </div>
        <div class="col-md-2">
            <input type="button" name="search" id="btn_search" value="Search" class="btn btn-primary" />
        </div>
    </div>
    </br>
    <div class="row">
        <div class="col-md-8">
            <div id="purchase_order">
                <table class="table table-bordered">
                    <tr>
                        <th width="5%">Room ID</th>
                        <th width="30%">Room Name</th>
                        <th width="40%">Room Type</th>
                        <th width="15%">Beds</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row["roomID"]; ?></td>
                            <td><?php echo $row["roomname"]; ?></td>
                            <td><?php echo $row["roomtype"]; ?></td>
                            <td><?php echo $row["beds"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>


<?php

echo '</div></div>';
include "footer.php";
mysqli_close($db_connection); //close the connection once done
?>