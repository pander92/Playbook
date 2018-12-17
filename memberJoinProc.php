<?php

    require_once("conn/connPlay.php");
    // process the form vars from memberJoin.php
    // pass the incoming form vars to "regular" vars
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $user = $_POST['user'];
    $pswd = $_POST['pswd'];

    // hash the password
    $hashedPswd = password_hash($pswd, PASSWORD_DEFAULT);

    // query the db to insert the new mbr
    // do NOT insert the regular pswd -- use hashed version !!!
    $query = "INSERT INTO members(firstName, lastName, email, user, pswd, isOW, isLoL) 
    VALUES('$firstName', '$lastName', '$email', '$user', '$hashedPswd', 0, 0)";

    mysqli_query($conn, $query);

    // if it worked, make folders for the new member
    if(mysqli_affected_rows($conn) == 1) { // if it worked
        
        // it worked, so make folders for this new mbr
        $mbrFolder = "members/" . $user;
        mkdir($mbrFolder, 0777);
        
        // inside the main user folder, make subfolders:
        mkdir($mbrFolder . "/images", 0777);
        mkdir($mbrFolder . "/audio", 0777);
        mkdir($mbrFolder . "/video", 0777);
        mkdir($mbrFolder . "/pdf", 0777);
        
        // make a starter image record in images
        // what is the id of the new member?
        $IDmbr = mysqli_insert_id($conn);
        
        $query_images = "INSERT INTO images(imgName, foreignID, catID) VALUES('pic-coming-soon.png', '$IDmbr', 3)";
        
        mysqli_query($conn, $query_images);
        
        // if we got this far, let's tell the user:
        $msg = "Welcome " . $firstName . "! Thank you for joining! You will now be redirected to the Login 
        page!";
        // redirect to login page in 5 seconds
        header("Refresh:2; url=login.php", true, 303);
        
    } else { // it failed
        
        $msg = "Sorry! Couldn't sign you up! Please try again!";
        header("Refresh:4; url=memberJoin.php", true, 303);

    }

?>

<!DOCTYPE html>
<html lang="en-us">

<head>

    <meta charset="utf-8">
    <title>Member Join Processor</title>
    
</head>
    
<body>
    
    <h1 align="center"><?php echo $msg; ?></h1>

</body>

</html>