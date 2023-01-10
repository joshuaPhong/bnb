<?php
include 'config.php';
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo  "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
$todate = $_POST['to_date'];
$fromdate = $_POST['from_date'];

if (isset($_POST["from_date"], $_POST["to_date"])) {
    $orderData = "";
    $query = "select * from room where roomID not in (select roomID from booking where checkindate <= '$fromdate' and checkoutdate >= '$todate')";
    $result = mysqli_query($db_connection, $query);

    $orderData .= '
    <table class="table table-bordered">
    <tr>
    <th width="5%">Room ID</th>
    <th width="30%">Room Name</th>
    <th width="40%">Room Type</th>
    <th width="15%">Beds</th>
    
    </tr>';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $orderData .= '
            <tr>
            <td>' . $row["roomID"] . '</td>
            <td>' . $row["roomname"] . '</td>
            <td>' . $row["roomtype"] . '</td>
            <td>' . $row["beds"] . '</td>
    
            </tr>';
        }
    } else {
        $orderData .= '
        <tr>
        <td colspan="5">No rooms Found</td>
        </tr>';
    }
    $orderData .= '</table>';
    echo $orderData;
}