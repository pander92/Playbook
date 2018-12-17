<?php

    require_once("conn/connPlay.php");
    session_start();
    // process the form vars from memberJoin.php
    // pass the incoming form vars to "regular" vars
    $LoLuser = $_POST['LoLuser'];
    $LoLPrimaryRole = $_POST['LoLPrimaryRole'];
    $LoLSecondaryRole = $_POST['LoLSecondaryRole'];
    $LoLRank = $_POST['LoLRank'];
    $LoLChamp1 = $_POST['LoLChamp1'];
    $LoLChamp2 = $_POST['LoLChamp2'];
    $LoLChamp3 = $_POST['LoLChamp3'];
    $foreignID = $_SESSION['IDmbr'];

    


    $query = "SELECT * FROM members WHERE IDmbr='$foreignID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    
    

    if($row['isLoL'] == 1) {
        
        //query to update league record
        $queryL= "UPDATE league SET 
        LoLuser = '$LoLuser', 
        LoLPrimaryRole = '$LoLPrimaryRole', 
        LoLSecondaryRole = '$LoLSecondaryRole', 
        LoLRank = '$LoLRank', 
        LoLChamp1 = '$LoLChamp1', 
        LoLChamp2 = '$LoLChamp2', 
        LoLChamp3 = '$LoLChamp3', 
        WHERE foreignID='$foreignID'";
        
             
    } else {
    // query the db to insert the new info
        
    $queryL = "INSERT INTO league(LoLuser, LoLPrimaryRole, LoLSecondaryRole, LoLRank, LoLChamp1, LoLChamp2, LoLChamp3, foreignID) VALUES('$LoLuser', '$LoLPrimaryRole', '$LoLSecondaryRole', '$LoLRank', '$LoLChamp1', '$LoLChamp2', '$LoLChamp3', '$foreignID')";
    }
    
    mysqli_query($conn, $queryL);
    

    // if it worked, make folders for the new member
    if(mysqli_affected_rows($conn) == 1) { // if it worked
        
        $isLoLquery = "UPDATE members SET isLoL=1 WHERE IDmbr = '$foreignID'";
        
        mysqli_query($conn, $isLoLquery);
        
        // if we got this far, let's tell the user:
        $msg = "Thank you for adding your League profile! You will now be redirected to the profile 
        page.";
        // redirect to login page in 5 seconds
        header("Refresh:5; url=profile.php", true, 303);
        
    } else { // it failed
        
        $msg = "Sorry! Couldn't add your League info! Please try again.";
        header("Refresh:4; url=profile.php", true, 303);

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