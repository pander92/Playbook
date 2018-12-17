<?php

    session_start();

    $msg = "Hello";

    if(isset($_SESSION['user'])) { // if user is logged in
       
        $IDmbr = $_SESSION['IDmbr'];
        // pass the incoming form vars to "regular" vars
        $gamesPlayed = $_POST['gamesPlayed'];
        $aboutMe = $_POST['aboutMe'];

        require_once("conn/connPlay.php");

        // escape all strings
        $gamesPlayed = mysqli_real_escape_string($conn, $gamesPlayed);
        $aboutMe = mysqli_real_escape_string($conn, $aboutMe);

        //$query = "INSERT INTO members" -- "C" in CRUD 
        //$query = "SELECT * FROM members" -- "R" in CRUD ($result)
        // save the changes to the members table: this is the "U" in CRUD
        $query = "UPDATE members SET gamesPlayed='$gamesPlayed', aboutMe='$aboutMe' WHERE IDmbr='$IDmbr'";
        
        mysqli_query($conn, $query);
        
        // did it work?
        if(mysqli_affected_rows($conn) == 1) {
            $msg = "Profile updated successfully.";
        } else if(mysqli_affected_rows($conn) == 0) {
            $msg = "Huh? There were no changes to save!";
        } else { // returned -1
             $msg = "Sorry! Could not save changes due to an error!";
        }
        
        // add this to msg, whether it worked or not
        $msg .= "<br/>You will now be redirected to your profile page...";
        // redirect to profile page, whether it worked or not
        header("Refresh:5; url=profile.php", true, 303);

    
    } else { // user is not logged in
        
        header("Location: login.php"); // bounce the intruder
        
    } 

?>

<!DOCTYPE html>
<html lang="en-us">

<head>

    <meta charset="utf-8">
    <title>Edit Profile Processor</title>
    
</head>
    
<body>
    
    <h1>
        <?php echo $msg; ?>
    </h1>
    
</body>

</html>