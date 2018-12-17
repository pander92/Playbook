<?php

    // 1.) authenticate user (redirect if not logged in)
    session_start();
    // check for user as session var
    if(isset($_SESSION['user'])) { // if we will let them stay
        
        // pass SESSION vars to "regular" vars:
//        $user = $_SESSION['user']; // for image file path
//        $IDmbr = $_SESSION['IDmbr'];
//        $firstName = $_SESSION['firstName'];
        
        if(isset($_GET['searchID'])){
          $IDmbr = $_GET['searchID'];
        }else{
          $IDmbr = $_SESSION['IDmbr'];
        }
        
        require_once("conn/connPlay.php");
        // what do we do if logged in user gets to stay?
        // load their own personal data from members table
        $query = "SELECT * FROM members WHERE IDmbr='$IDmbr'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        // testing, 1, 2, 3.. did we get the data to load..??
        $msg = $row['user'];
        
        if(isset($_GET['searchID'])){
          //Use the user/name for a different user
          $user = $row['user'];
          $firstName = $row['firstName'];
        }else{
          //Use your own user/name from the session variables
          $user = $_SESSION['user'];
          $firstName = $_SESSION['firstName'];
        }
        
    } else { // Intruder Alert! Session var for user not set !
        // redirect to the login.php page
        header("Location: login.php");
    }
    
    // if they clicked the Log Out
    if(isset($_GET['logout'])) {
        session_destroy();
        $msg = 'You are logged out! Now redirecting..';
        header("Refresh:4; url=login.php", true, 303);
    }

    // query the db for the current Main Profile Pic
    $queryMainPic = "SELECT * FROM images WHERE catID=3  AND foreignID='$IDmbr'";

    $resultMainPic = mysqli_query($conn, $queryMainPic);

    // there is, by definition, only ONE main pic
    // hence, we do not need a while loop for $row
    $rowMainPic = mysqli_fetch_array($resultMainPic);
    $mainPic = $rowMainPic['imgName'];

    //#####GAME QUERIES START#######
    //check for an overwatch record
    $queryOW = "SELECT * FROM overwatch WHERE foreignID='$IDmbr'";
    $resultOW = mysqli_query($conn, $queryOW);
    $rowOW = mysqli_fetch_array($resultOW);


    //check for an league record
    $queryLoL = "SELECT * FROM league WHERE foreignID='$IDmbr'";
    $resultLoL = mysqli_query($conn, $queryLoL);
    $rowLoL = mysqli_fetch_array($resultLoL);

    //##GAME QUERIES END ########

    // load all other pics for that user
    $queryOtherPics = "SELECT * FROM images
    WHERE foreignID='$IDmbr' AND catID=4
    ORDER BY IDimg DESC";

    $resultOtherPics = mysqli_query($conn, $queryOtherPics);

    
?>


<?php
    $title = "Page Title";
?>


<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<head>
<link href="css/play.css" rel="stylesheet">
</head>

<main>

    <div id="profile-container">
        
        <header id="profile-header">
            <h3><?php echo $msg; ?></h3>
            
            
        </header>
        
        <div id="myPics">
            <h5>Games Played</h5>
            <?php
                // output game logos here as thumbnail gallery
                if($row['isLoL'] == 1) {
                    echo '<img src="images/LoLlogo.png" height="auto" width="20%">';}
                    
                 
                if($row['isOW']==1){
                    echo '<img src="images/OWlogo.png" height="auto" width="20%">';}
//                 else {
//                while($rowPics = mysqli_fetch_array($resultPics)) {
//                    // do something with each pic
//                    echo '<img src="images/' . $rowPics['imgName'] . '" height="80%" width="auto" id="' . $rowPics['IDimg'] . '" name="' . $rowPics['imgName'] . '">'; 
//                }
//                }
            ?>
                        
        </div>
        
<!--        <div id="profile-pics">-->
            
            <!-- ###***### UPLOAD PIC CODE START ###***###-->
             <?php 
              if($user == $_SESSION['user']){
                echo '<div id="upload-pic">
                
                <p>Upload Picture:</p>
                <form method="post" action="upload-pic-proc.php" enctype="multipart/form-data"
                style="padding:0; background-color:transparent; border:0px; width:100%; margin:0; text-align:left">
                
                    <input type="file" name="fileToUpload" style="border:0; width:100%; margin-bottom:10px">
                    
                    <input type="checkbox" name="isMainPic" id="isMainPic" value="isMainPic" class="chkbox">
                    
                    <label for="isMainPic">Set Profile Pic</label>

                    <button style="padding:5px 15px; margin-top:10px; display:block; width:100%">
                        Save Image
                    </button>
                
                </form>
              <div id="newPicFileName"></div>

            </div>';//<!-- close upload-pic -->
            ;
                }?>
            
            <!-- ###***### UPLOAD PIC CODE END ###***###-->
            <div id="mainPicDiv">
            <!-- if the user has no profile pic yet (because they haven't uploaded one yet), use the default Coming Soon generic profile pic, which is in the regular main-level images foler, not in the user's own images folder -->
            <?php
                
                // if main profile pic is the generic Coming Soon pic
                if($mainPic == 'pic-coming-soon.png') {
                    
                    // the Coming Soon Pic is in main images folder
                    $imgPath = 'images/' . $mainPic;
                    
                } else { // main pic not default/generic Coming Soon
                    
                    // the user's Profile pic is in their own folder
                    $imgPath = 'members/' . $user . '/images/' . $mainPic;
                }
            
                // output the user profile image
                echo '<img src="' . $imgPath . '"  
                width="100%" height="auto" id="mainPic">';

            ?>
                
            <?php if($user == $_SESSION['user']){ ?>
            <!-- save the newly selected profile pic -->
            <form method="post" action="set-profile-pic.php" id="setPic" style="background-color:transparent; border:0; padding:0; text-align:left; display:none">
                
                <input type="hidden" name="newPicID" id="newPicID">
                
                <input type="checkbox" name="newPic" id="newPic" checked style="width:25px; display:inline">
                <label for="newPic">Save As Profile Pic</label>
                
                <input type="submit" value="Save" style="width:55px">
                
            </form>
                <?php } ?>
        </div>
<!--        </div>-->
        
        <div id="profile-details">
            
            <?php 
              if($user == $_SESSION['user']){
              echo '<p style="text-align:right">
                
                    <button id="edit-btn" onclick="toggleEdit()">Edit</button>
                
                    </p> '; 
              }
            ?>
            
            <div id="profile-uneditable">
                
                <p>Playbook Username: <?php echo $row['user']; ?></p>
            
                <p style="display:none;">Name: <?php echo $row['firstName'] . 
                    " " . $row['lastName']; ?></p>

                <p>Member Since: <?php echo date('M. D. d, Y', strtotime($row['joinTime'])); ?></p>

                <p>Games Played: <?php echo $row['gamesPlayed']; ?></p>
                <hr>
                    
                <p>About Me: <?php echo $row['aboutMe']; ?></p>
            
            
            
            </div><!-- close #profile-uneditable -->
            

            
            <!-- Edit Me Box is hidden on page load -->
            <div id="profile-editable" style="display:none">
    
                <form method="post" action="profile-editable-proc.php">

                    <h3>Edit Your Personal Details</h3>

                    <!-- Company, Job Title, Hobbies, About Me -->
                    <p><textarea name="gamesPlayed" cols="50" rows="10"><?php echo $row['gamesPlayed']; ?></textarea></p>

                    <p><textarea name="aboutMe" cols="50" rows="10"><?php echo $row['aboutMe']; ?></textarea></p>

                    <p><input type="submit" name="submitEditProfile" 
                               value="Save Changes"></p>

                </form>

            </div><!-- close #profile-edit-me -->
            
        </div><!-- close mainPicDiv -->
        <!--start overwaqtch stat block-->
        <?php if($row['isOW']==1){ ?>
        <div id="OWDetails">
            <div id="OWDlogo"><img style="width:90%; height:auto; " src="images/OWlogo.png"></div>
            <div id="OWDstats">
                <p>
                    <strong>Username:</strong> <?php echo $rowOW['OWuser'];?>
                </p>
                <p>
                    <strong>Console:</strong> <?php echo $rowOW['OWconsole'];?>
                </p>
                <p>
                    <strong>Primary Role:</strong> <?php echo $rowOW['OWPrimaryRole'];?><br>
                    <strong>Secondary Role:</strong> <?php echo $rowOW['OWSecondaryRole'];?>
                </p>
            </div>
            <figure id="OWDrank">
                <?php
                echo '<img style="width:75px; height:75px;" src="images/OWRank/' . $rowOW['OWRank'] . '.png">';
                
                echo '<figcaption style="text-align:center;">' . $rowOW['OWRank'] . '</figcaption>';
                
                ?>
            </figure>
            <div id="OWDheroes">
                <h5>Top Heroes:</h5>
                <p>
                    1. <?php echo $rowOW['OWHero1'];?>
                </p>
                <p>
                    2. <?php echo $rowOW['OWHero2'];?>
                </p>
                <p>
                    3. <?php echo $rowOW['OWHero3'];?>
                </p>
            </div>
        </div>
        <?php } ?>
        <!--end overwatch stat block -->
        
        
        <!--start league statblock-->
        <?php if($row['isLoL']==1){ ?>
        <div id="LoLDetails">
            <div id="LoLDlogo"><img style="width:90%; height:auto; " src="images/LoLlogo.png"></div>
            <div id="LoLDstats">
                <p>
                    <strong>Username:</strong> <?php echo $rowLoL['LoLuser'];?>
                </p>
                <p>
                    <strong>Primary Role:</strong> <?php echo $rowLoL['LoLPrimaryRole'];?><br>
                    <strong>Secondary Role:</strong> <?php echo $rowLoL['LoLSecondaryRole'];?>
                </p>
            </div>
            <figure id="LoLDrank">
                <?php
                echo '<img style="width:75px; height:75px;" src="images/LoLRank/' . $rowLoL['LoLRank'] . '.png">';
                
                echo '<figcaption style="text-align:center;">' . $rowLoL['LoLRank'] . '</figcaption>';
                
                ?>
            </figure>
            <div id="LoLDchamps">
                <h5>Top Champions:</h5>
                <p>
                    1. <?php echo $rowLoL['LoLChamp1'];?>
                </p>
                <p>
                    2. <?php echo $rowLoL['LoLChamp2'];?>
                </p>
                <p>
                    3. <?php echo $rowLoL['LoLChamp2'];?>
                </p>
            </div>
        </div>
        <?php } ?>
        <!--end league stat block -->
        
        <!--display forms to add game specific information -->
        <?php 
        if($user == $_SESSION['user']){
                echo '<div id="gameStats">
            <select id="addGame" onchange="displayGameForm()">
                <option value=0>Add Game Data</option>
                <option value=1>Overwatch</option>
                <option value=2>League of Legends</option>
            </select>
        </div>';}
        ?>
        
        <!-- Overwatch Form start -->
        <form method="post" action="OWProc.php" id="op1" style="display:none;">

                    <h3>Edit your Overwatch Details!</h3>
                    <p>
                        Select your Console:
                        <select name="OWconsole" id="OWconsole">
                            <option value='PC'>PC</option>
                            <option value='PS4'>PS4</option>
                            <option value='Xbox'>Xbox</option>
                        </select>
                    </p>
                   
                    <p><input type="text" name="OWuser" placeholder="Username" value="<?php echo $row['OWuser']; ?>"></p>

                    <p>
                        Select your Primary Role:
                        <select name="OWPrimaryRole" id="OWPrimaryRole">
                            <option value="DPS">DPS</option>
                            <option value="Healer">Healer</option>
                            <option value="Tank">Tank</option>
                            <option value="Flex">Flex</option>
                        </select>
                    </p>
            
                    <p>
                        Select your Secondary Role:
                        <select name="OWSecondaryRole" id="OWSecondaryRole">
                            <option value="DPS">DPS</option>
                            <option value="Healer">Healer</option>
                            <option value="Tank">Tank</option>
                            <option value="Flex">Flex</option>
                            <option value="None">None</option>
                        </select>
                    </p>

                    <p>
                        Select your Rank:
                        <select name="OWRank" id="OWRank">
                            <option value="Unranked">Unranked</option>
                            <option value="Bronze">Bronze</option>
                            <option value="Silver">Silver</option>
                            <option value="Gold">Gold</option>
                            <option value="Platinum">Platinum</option>
                            <option value="Diamond">Diamond</option>
                            <option value="Master">Master</option>
                            <option value="Grandmaster">Grandmaster</option>
                        </select>
                    </p>
            
                    <p>
                        Select your Top 3 Heroes:
                        <select name="OWHero1" id="OWHero1">
                            <option>Select Hero 1</option>
                        </select>
                        
                        <select name="OWHero2" id="OWHero2">
                            <option>Select Hero 2</option>                           
                        </select>
                        
                        <select name="OWHero3" id="OWHero3">
                            <option>Select Hero 3</option> 
                        </select>
                    </p>
            
                    

                    <p><input type="submit" name="submitEditProfile" 
                               value="Save"></p>

                </form> <!--Overwatch form end-->
        
        <!-- League Form start -->
        <form method="post" action="LoLProc.php" id="op2" style="display:none;">

                    <h3>Add or Edit your League of Legends Details!</h3>
                   
                    <p><input type="text" name="LoLuser" placeholder="Username" value="<?php echo $row['LoLuser']; ?>"></p>

                    <p>
                        Select your Primary Role:
                        <select name="LoLPrimaryRole" id="LoLPrimaryRole">
                            <option value="Top Lane">Top Lane</option>
                            <option value="Jungle">Jungle</option>
                            <option value="Mid Lane">Mid Lane</option>
                            <option value="Support">Support</option>
                            <option value="Marksman">Marksman</option>
                            <option value="Flex">Flex</option>
                        </select>
                    </p>
            
                    <p>
                        Select your Secondary Role:
                        <select name="LoLSecondaryRole" id="LoLSecondaryRole">
                            <option value="Top Lane">Top Lane</option>
                            <option value="Jungle">Jungle</option>
                            <option value="Tank">Mid Lane</option>
                            <option value="Mid Lane">Mid Lane</option>
                            <option value="Support">Support</option>
                            <option value="Marksman">Marksman</option>
                            <option value="Flex">Flex</option>
                        </select>
                    </p>

                    <p>
                        Select your Rank:
                        <select name="LoLRank" id="LoLRank">
                            <option value="Unranked">Unranked</option>
                            <option value="Bronze">Bronze</option>
                            <option value="Silver">Silver</option>
                            <option value="Gold">Gold</option>
                            <option value="Platinum">Platinum</option>
                            <option value="Diamond">Diamond</option>
                            <option value="Master">Master</option>
                            <option value="Challenger">Challenger</option>
                        </select>
                    </p>
            
                    <p>
                        Select your Top 3 Champions:
                        <select name="LoLChamp1" id="LoLChamp1">
                            <option>Select Champion 1</option>
                        </select>
                        
                        <select name="LoLChamp2" id="LoLChamp2">
                            <option>Select Champion 2</option>                           
                        </select>
                        
                        <select name="LoLChamp3" id="LoLChamp3">
                            <option>Select Champion 3</option> 
                        </select>
                    </p>
            
                    

                    <p><input type="submit" name="submitEditProfile" 
                               value="Save"></p>

                </form>
                <!--League form end-->
        <footer id="myOtherPics">
            
            <h4>Photo Gallery</h4>
            <?php
                // output user pics here as thumbnail gallery

                while($rowOtherPics = mysqli_fetch_array($resultOtherPics)) {
                    if($rowOtherPics['imgName'] == 'pic-coming-soon.png'){
                        
                    } else {
                    // do something with each pic
                    echo '<img src="members/' . $user . '/images/' . $rowOtherPics['imgName'] . '" height="100%" width="auto" onclick="displayGallery()" id="' . $rowOtherPics['IDimg'] . '" name="' . $rowOtherPics['imgName'] . '">'; 
                    }
                }
            
            ?>
                        
     
        </footer>
        <div id="galleryPicDiv" style="display:none">
                <img id="galleryPic" src="">
                <button id="closeGallery" onclick="closeGallery()">Close</button>
            </div>
    
    </div><!-- close #profile-container -->
    
    <script>
        function displayGallery(){
            
        document.getElementById("galleryPicDiv").style.display="block";
        document.getElementById("galleryPic").src = event.target.src;
        }
        
        function closeGallery(){            document.getElementById("galleryPicDiv").style.display="none";
        }
        
        
        function displayGameForm() {
            
            document.getElementById("op1").style.display = 'none';
            document.getElementById("op2").style.display = 'none';
            
            var gameSelect = document.getElementById("addGame").value;
            document.getElementById("op" + gameSelect).style.display = 'block';
        }
        
        // change the Main Profile Pic to be the clicked thumbnail image
        function swapImage() {
            
            // set the value of the hidden input field to be the ID of the just-clicked image that called this func
            document.getElementById("newPicID").value = event.target.id;
            
            // output the name of the just-clicked image under said image in its own little div
            const newPicFileName = document.getElementById('newPicFileName');
            newPicFileName.innerHTML = "File: " + event.target.name + " ID: " + event.target.id;
            
            const setPic = document.getElementById('setPic');
            setPic.style.display = "block";
            // event.target is the "thing" that called the function
            // in this case, event.target is the thumbnail image
            const mainPic = document.getElementById('mainPic');
            // set source of Main Pic = source of clicked thumb pic
            mainPic.src = event.target.src;
            
        }
    
        // toggle uneditable and editable member details boxes
        function toggleEdit() {
            
            // grab both boxes and the Edit button
            const profileUneditable = document.getElementById('profile-uneditable');
            const profileEditable = document.getElementById('profile-editable');
            const editBtn = document.getElementById('edit-btn');
            
            // toggle visibility: hide visible, show invisible 
            if(editBtn.innerHTML == "Edit") {
                
                editBtn.innerHTML = "Cancel";
                profileEditable.style.display = "block"; // show hidden Edit Form/div
                profileUneditable.style.display = "none"; // show uneditable details
                
            } else { // edit button already says "Cancel"
                
                editBtn.innerHTML = "Edit";
                profileEditable.style.display = "none"; // show hidden Edit Form/div
                profileUneditable.style.display = "block"; // show uneditable details
                
            } // end if-else

        } // toggleEdit()
     //##############OVERWATCH HERO SELECT MENU#############
        var OWHero1 = document.getElementById("OWHero1"); 
        var OWHero2 = document.getElementById("OWHero2");
        var OWHero3 = document.getElementById("OWHero3");
        var OWheroes = ["Ana", "Bastion", "Brigitte", "D.Va", "Doomfist", "Genji", "Hanzo", "Junkrat", "Lucio", "Mcree", "Mei", "Mercy", "Moira", "Orisa", "Pharah", "Reaper", "Reinhardt", "Rodahog", "Soldier 76", "Sombra", "Symmetra", "Torbjorn", "Tracer", "Widowmaker", "Winston", "Zarya", "Zenyatta"]; 

        for(var i = 0; i < OWheroes.length; i++) {
        var opt = OWheroes[i];
        var el1 = document.createElement("option");
        el1.textContent = opt;
        el1.value = opt;
        OWHero1.appendChild(el1);
            
        var el2 = document.createElement("option");
        el2.textContent = opt;
        el2.value = opt;
        OWHero2.appendChild(el2);
        
        var el3 = document.createElement("option");
        el3.textContent = opt;
        el3.value = opt;
        OWHero3.appendChild(el3);
        }
            
        //##############LEAGUE CHAMP SELECT MENU############
        var LoLChamp1 = document.getElementById("LoLChamp1"); 
        var LoLChamp2 = document.getElementById("LoLChamp2");
        var LoLChamp3 = document.getElementById("LoLChamp3");
        var LoLChamps = ["Aatrox", "Ahri", "Akali", "Alistar", "Amumu", "Anivia", "Annie", "Ashe", "Aurelion Sol", "Azir", "Bard", "Blitzcrank", "Brand", "Braum", "Caitlyn", "Camille", "Cassiopeia", "ChoGath", "Corki", "Darius", "Diana", "Dr. Mundo", "Draven", "Ekko", "Elise", "Evelynn", "Ezreal", "Fiddlesticks", "Fiora", "Fizz", "Galio", "Gangplank", "Garen", "Gnar", "Gragas", "Graves", "Hecarim", "Heimerdinger", "Illaoi", "Irelia", "Ivern", "Janna", "Jarvan IV", "Jax", "Jayce", "Jhin", "Jinx", "KaiSa", "Kalista", "Karma", "Karthus", "Kassadin", "Katarina", "Kayle", "Kayn", "Kennen", "KhaZix", "Kindred", "Kled", "KogMaw", "LeBlanc", "Lee Sin", "Leona", "Lissandra", "Lucian", "Lulu", "Lux", "Malphite", "Malzahar", "Maokai", "Master Yi", "Miss Fortune", "Mordekaiser", "Morgana", "Nami", "Nasus", "Nautilus", "Nidalee", "Nocturne", "Nunu", "Olaf", "Orianna", "Ornn", "Pantheon", "Poppy", "Quinn", "Rakan", "Rammus", "RekSai", "Renekton", "Rengar", "Riven", "Rumble", "Ryze", "Sejuani", "Shaco","Shen","Shyvana", "Singed", "Sion", "Sivir", "Skarner", "Sona", "Soraka", "Swain", "Syndra", "Tahm Kench", "Taliyah", "Talon", "Taric", "Teemo", "Thresh", "Tristana", "Trundle", "Tryndamere", "Twisted Fate", "Twitch", "Udyr", "Urgot", "Varus", "Vayne", "Veigar", "VelKoz", "Vi", "Viktor", "Vladimir", "Volibear", "Warwick", "Wukong", "Xayah", "Xerath", "Xin Zhao", "Yasuo", "Yorick", "Zac","Zed", "Ziggs", "Zilean", "Zoe", "Zyra"]; 

        for(var i = 0; i < LoLChamps.length; i++) {
        var champ = LoLChamps[i];
        var opt1 = document.createElement("option");
        opt1.textContent = champ;
        opt1.value = champ;
        LoLChamp1.appendChild(opt1);
            
        
        var opt2 = document.createElement("option");
        opt2.textContent = champ;
        opt2.value = champ;
        LoLChamp2.appendChild(opt2);
            
        var opt3 = document.createElement("option");
        opt3.textContent = champ;
        opt3.value = champ;
        LoLChamp3.appendChild(opt3);
}
    </script>


</main>

<?php include 'includes/footer.php'; ?>







