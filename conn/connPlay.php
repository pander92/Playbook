<?php

    // connect to MySQL as a whole (pg. 29)
    $conn = mysqli_connect("localhost", "teravotc_playboo", "falltwilight314") or die("Couldn't Connect to MySQL!");

    // connect to a specific database
    mysqli_select_db($conn, "teravotc_playbook") or die("Couldn't Connect to Database!");

?>