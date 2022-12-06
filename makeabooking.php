<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Make a Booking</title>
</head>
<!-- this page is for the customer to make a booking. it is grouped into two parts. make a booking and a search for room availability -->

<body>
    <!-- page heading and links to other pages -->
    <h1>Make a Booking</h1>
    <p><a href='listCurrentBookings.html'>[Return to the Bookings listing]</a><a href="index.html">[Return to Main
            Page]</a>
    </p>
    <!-- the firat part of the page is a fortm for booking -->
    <fieldset>
        <legend>Customer ID#</legend>
        <!-- not connected yet -->
        <form method="POST"
            action="">
            <!-- a drop down menu for the customer to select their choice of room with two illistrative peices of data -->
            <p>
                <label for="room">Please select a room (name, type, beds):</label>
                <select id="room"
                    name="room">
                    <option value="kelly">Kelly, S, 5</option>
                    <option value="herman">Herman, D</option>
                </select>
                <label>*</label>
            </p>
            <!-- a date picker for the customer to select thier checkin date. this is a required field/ client side data validation -->
            <!-- there is no validation to make sure the date is within a suitable range, i.e. not the past -->
            <p>
                <label for="checkin">Checkin date:</label>
                <input type="date"
                    id="checkin"
                    name="checkin"
                    required>
                <label>*</label>
            </p>
            <!-- a date picker for the check out date, is required* -->
            <p>
                <label for="checkout">Checkout Date: </label>
                <input type="date"
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