<?php

    if(!isset($_SESSION)) {
        // session is required to set and access $_SESSION variables, which are stored on the server and are 
        // used to authenticate users
        session_start();
        // Ending a session in 30 minutes from the starting time.
        // $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
    }

    require_once("conn/connPlay.php");

    // ###***### ###***## ###***## ###***##
    // ###***### LOG IN PROCESSOR ###***###
    // ###***### ###***## ###***## ###***##

    // memberJoinProc.php redirects here on successful registration of new member

    // if the Log In form was submitted
    // the form variables are all set
    // only run this code on submit of form
    if(isset($_POST['loginSubmit'])) {
        $user = $_POST['user']; // process the login attempt
        $pswd = $_POST['pswd'];       
        // query the database for the user
        $query = "SELECT * FROM members WHERE user='$user'";    
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);      
        // if we found results, output their data as a test
        // and compare pswd they entered to the hashed pswd in db
        if(mysqli_num_rows($result) == 1) {
            // check to see if pswd entered matches hashed
            if(password_verify($pswd, $row['pswd'])) {
                // we made it this far, so user n pswd both good
                // welcome the user and provide log out link
                $msg = 'Welcome ' . $row['firstName'] . '!<br/>';
                $msg .= '<a href="?logout=yep">Log Out</a>';
                
                // Make SESSION variables for Authentication
                $_SESSION['user'] = $row['user'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['IDmbr'] = $row['IDmbr'];
                
                // did the session vars get initialized ??
                $msg .= "<br/>Username: " . $_SESSION['user'];
                
                // provide link to personal profile page
                $msg .= '<br/><a href="profile.php">My Profile</a>';
                
                $msg .= "<br/>You will now be redirected to your Profile Page...";
                
                echo $msg;
                
                // redirecting...
                header("Refresh:4; url=profile.php", true, 303);
                
            } // end if(password_verify($pswd, $row['pswd'])) 
        } // end if(mysqli_num_rows($result) == 1)  
    } // end if(isset)

    // if user clicked Log Out, a URL Var called logout=yep
    // got appended to the URL: login.php?logout=yep
    // the following if statement looks for the logout var, and 
    // if it finds it, ends the session, destroying all SESSION vars in the process
    if(isset($_GET['logout'])) {
        session_destroy();
        $msg = 'You are logged out';
        echo $msg;
    }

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<link href="css/play.css" rel="stylesheet">
</head>
<main>

    <h1 id="welcome">Welcome to Playbook!</h1>
    <!-- buttons side by side, display relevant form on click-->
    <div id="btns">
    <button id="loginBtn" onclick="displayLogin()">Log In</button>
        <br>
    <button id="signUp" onclick="displaySignUp()">Sign Up</button>
    </div>
    
    <form id="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:none">
        <p>
            <input type="text" name="user" placeholder="Username" required>
        </p>
        
        <p>
            <input type="password" name="pswd" placeholder="Password" required>
        </p>
        
        <p>
            <input type="submit" name="loginSubmit" value="Log In">
        </p>
        <h4 style="text-align:center; 
                  font-size:0.9rem">
            <?php echo $msg; ?>
        </h4>
        
    </form>
    
    <form method="post" id="signUpForm" action="memberJoinProc.php"                 onsubmit="return validatePassword()" style="display:none">
        
        <p><input type="text" name="firstName" id="firstName" placeholder="First Name" required></p>
        
        <p><input type="text" name="lastName" id="lastName" placeholder="Last Name" required></p>
        
        <p><input type="email" name="email" id="email" placeholder="Email" required></p>
        
        <p><input type="text" name="user" id="user" placeholder="Username" required></p>
        
        <p><input type="password" name="pswd" id="pswd" placeholder="Password" required></p>
    
        <p><input type="password" name="pswd2" id="pswd2" placeholder="Re-Enter Password" required></p>
        
        
        <p><input type="submit" name="submit" id="submit" value="Submit"></p>
    
    </form>

</main>

<script>
    
        function displayLogin(){
            document.getElementById('loginForm').style.display = "block"
        }
    
        function displaySignUp(){
            document.getElementById('signUpForm').style.display = "block"
        }
    
        function validatePassword() {
            // get the pswds to compare
            const pswd = document.getElementById("pswd").value;
            const pswd2 = document.getElementById("pswd2").value;
            
            // do they match?
            if(pswd != pswd2) {
                alert("Passwords Don't Match!");
                return false;
            }
        }
    
</script>



</html>





