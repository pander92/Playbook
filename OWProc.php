<?php

    require_once("conn/connPlay.php");
    session_start();
    // pass the incoming form vars to "regular" vars
    $OWconsole = $_POST['OWconsole'];
    $OWuser = $_POST['OWuser'];
    $OWPrimaryRole = $_POST['OWPrimaryRole'];
    $OWSecondaryRole = $_POST['OWSecondaryRole'];
    $OWRank = $_POST['OWRank'];
    $OWHero1 = $_POST['OWHero1'];
    $OWHero2 = $_POST['OWHero2'];
    $OWHero3 = $_POST['OWHero3'];
    $foreignID = $_SESSION['IDmbr'];


    $query = "SELECT * FROM members WHERE IDmbr='$foreignID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    
    

    if($row['isOW'] == 1) {
        
        $query = "UPDATE overwatch SET 
        OWconsole ='$OWconsole',
        OWuser = '$OWuser', 
        OWPrimaryRole = '$OWPrimaryRole', 
        OWSecondaryRole = '$OWSecondaryRole', 
        OWRank = '$OWRank', 
        OWHero1 = '$OWHero1', 
        OWHero2 = '$OWHero2', 
        OWHero3 = '$OWHero3', 
        foreignID = '$foreignID'
        WHERE foreignID='$foreignID'";
               
    } else {
    // query the db to insert the new info
    $query = "INSERT INTO overwatch(OWconsole, OWuser, OWPrimaryRole, OWSecondaryRole, OWRank, OWHero1, OWHero2, OWHero3, foreignID) 
    VALUES('$OWconsole', '$OWuser', '$OWPrimaryRole', '$OWSecondaryRole', '$OWRank', '$OWHero1', '$OWHero2', '$OWHero3', '$foreignID')";
    }

    mysqli_query($conn, $query);
    

    // if it worked, make folders for the new member
    if(mysqli_affected_rows($conn) == 1) { // if it worked
        
        $isOWquery = "UPDATE members SET isOW=1 WHERE IDmbr = '$foreignID'";
        
        mysqli_query($conn, $isOWquery);
        
        // make a starter image record in images
        // what is the id of the new member?
        //$IDmbr = mysqli_insert_id($conn);
        
        //$query_images = "INSERT INTO images(imgName, foreignID, catID, isMainPic) VALUES('pic-coming-soon.jpg', '$IDmbr', 3, 1)";
        
        //mysqli_query($conn, $query_images);
        
        // if we got this far, let's tell the user:
        $msg = "Thank you for adding your Overwatch profile! You will now be redirected to the profile 
        page.";
        // redirect to login page in 5 seconds
        header("Refresh:5; url=profile.php", true, 303);
        
    } else { // it failed
        
        $msg = "Sorry! Couldn't add your Overwatch info! Please try again.";
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
    <h1 align="center"><?php echo $lag; ?></h1>

</body>

</html>