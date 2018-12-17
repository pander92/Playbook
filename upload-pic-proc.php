<?php

    session_start(); // start session so we can use SESSION vars
	// if not logged in, send user to login form, and stop script
	if(!isset($_SESSION['IDmbr'])) {
		header('Location: login.php');
        // stop rest of the script from executing
		exit('You must be logged in.'); 
	}
    
    $IDmbr = $_SESSION['IDmbr']; // need $IDmbr to query the DB

    require_once("conn/connPlay.php");

    // ##**##**##** IMAGE UPLOADER ##**##**##**
    // ##**##This script processes the image uploaded in profile.php ##**##
    if(isset($_POST['isMainPic'])) { // is Set Main Pic checkbox was checked
        $isMainPic = 1; // the value in the database of isMainPic
    } else {
        $isMainPic = 0; // in the db isMainPic=0
    }
        
    // 1.) Save the Image File Name to the DB

    // get the incoming file, which is more than
    // just a file name. It includes size, type, etc.
    $fileToUpload = $_FILES['fileToUpload']['name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileType = $_FILES['fileToUpload']['type'];
    // file gets a temporary name during the upload process
    $fileTempName = $_FILES['fileToUpload']['tmp_name'];
	/** Common File Types:
		image/jpeg
		image/png
        image/gif
		application/json
		text/plain
		text/css
		text/js
        audio/mp3
        video/mp4
        application/pdf
	**/

    // A Boolean to keep track of whether the file is good to go or not
    $isOkay = 1; // assume file is good until proven otherwise

	// TEST #1: if file type begins with "image/"
	if(strpos($fileType, 'image/') !== 0) { // !== is not equal
		$msg = 'File uploaded is not an image.';
        $isOkay = 0; // Reject !! Cancel Upload !!!
	}

	// TEST #2: if the image file size is greater than 5 MB.
	if($fileSize > 5000000) {
		$msg = 'Uploaded file is too big! 5MB limit!';
        $isOkay = 0; // Reject !! Cancel Upload !!!
	}

	// TEST #3: Has the user already saved this same image (or another with the same file name) to the DB ?? Let's have a look:
	// does row exists, as would be created if upload is successful.
	$query = "SELECT * FROM images WHERE foreignID='$IDmbr' AND catID=3 AND imgName='$fileToUpload'";

	$result = mysqli_query($conn, $query);

	if(mysqli_num_rows($result) > 0) {
       $msg = "The image " . $fileToUpload . " has already been uploaded!";
       $isOkay = 0;  // Reject !! Cancel Upload !!!
	}

	// TEST #4: Does the actual image file itself already exist in the user's personal images folder? Let us have a look-see, shall we?
	$filePath = 'members/' . $_SESSION['user'] . '/images/' . $fileToUpload;

	if(file_exists($filePath)) {
		$msg = 'Image already uploaded.';
        $isOkay = 0;  // Reject !! Cancel Upload !!!
	}

    if($isOkay == 1) { // if the file passed each and every test
        
        // save the user's image to the images table
        // start with some feedback, whether upload works or not
        $msg = "File name: " . $fileToUpload;
        $msg .= "<br/>File size: " . $fileSize;
        $msg .= "<br/>File type: " . $fileType;
        
        // ready to save new pic to db, but first set all
        // existing pics to isMainPic=0, since you can only
        // have ONE Main Pic at a time !
        // this $query0 ensures that the new Main Pic replaces
        // the old Main Pic
        // IF the user checked the Set Profile Pic checkbox, go into the DB
        // and set all existing pics isMainPic=0
        if($isMainPic == 1) {
            $query0 = "UPDATE images SET catID=4 
            WHERE catID=3 AND foreignID='$IDmbr'";
            mysqli_query($conn, $query0);
            
            $query = "INSERT INTO images(foreignID, imgName, catID) VALUES('$IDmbr', '$fileToUpload', 3)";
        } else {
            $query = "INSERT INTO images(foreignID, imgName, catID) VALUES('$IDmbr', '$fileToUpload', 4)";
        }
           
        // assuming it's actually an image and is not too big
        // save the image file name to the images table, and be
        // sure to associate the image with this particular mbr
        // In CRUD, this is the "C" (Create):
        
        
        mysqli_query($conn, $query);
        
        // did it work..? if we "affected" one row, then YEST
        if(mysqli_affected_rows($conn) == 1) {
            $msg .= "<br/>Congrats! Image Saved to Database!";
        } else {
            $msg .= "<br/>Oops! Couldn't Save Image to Database!";
        }
        
        // 2.) Upload image to user's images folder
        if(move_uploaded_file($fileTempName, $filePath)) {
            // if it worked (if method returned true)
            $msg .= "<br/>Success! The file " . $fileToUpload . " has been uploaded. ";
        } else {
             $msg .= "<br/>Sorry, there was an error uploading your file!";
        } // end if-else move_uploaded_file()
        
    } // end if($isOkay == 1)

    // redirect back to Profile Page, no matter what :
    $msg .= "<br/>Back to your Profile Page...
    <br/>Now Redirecting...";
    header("Refresh:4; url=profile.php", true, 303);

    // ###*** END IMAGE SAVE TO DB AND UPLOAD SCRIPT ##**##

?>

<!DOCTYPE html>
<html lang="en-us">

<head>

    <meta charset="utf-8">
    <title>Image Upload Processor</title>
    
</head>
    
<body>
    
    <h1 align="center">
   
        <?php echo $msg; ?>
        
    </h1>

</body>

</html>




