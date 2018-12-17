<?php

    // connect to MySQL as a whole (pg. 29)
    $conn = mysqli_connect("localhost", "root", "mysql") or die("Couldn't Connect to MySQL!");

    // connect to a specific database
    mysqli_select_db($conn, "playBook") or die("Couldn't Connect to Database!");

?>