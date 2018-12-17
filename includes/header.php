<header>
    
    <div id="join-login">
        <ul>
        
        <?php
          session_start();
          // check for user as session var
          if(isset($_SESSION['user'])) { // If logged in, show Logout button
          
            echo '<li><a href="profile.php">My Profile</a></li> | ';
            echo '<li><a href="login.php?logout=yep">Log Out</a></li>';
          
          
          }else{// Otherwise, display Log In
          
            echo '<li><a href="join.php">Join</a></li> | ';
              
            echo '<li><a href="login.php">Log In</a></li>';
            
          
          }
        
        
        
        ?>
        
<!--            <li><a href="login.php">Log In</a></li> | -->
<!--            <li><a href="join.php">Join</a></li>-->
        </ul>  
        <!-- #######Start Search Form#######-->
        <form name="search-form" id="search-form" style="background-color:transparent; border: 0; padding: 0; display:block;" method="post" action="search-proc.php">
            
            <input type="search" name="search" id="search" placeholder="Search" onkeyup="validateSearch()">
            
            <input type="submit" name="submit" id="submit" value="GO" style="width:50px;" disabled>
        
        </form>
        <!-- #######End Search Form#######-->
    </div>
    
    <div id="banner">
        <h2>Welcome to Playbook</h2>
    </div>
    
    <script>
        function validateSearch() {
            const search = document.getElementById("search").value;
            const submit = document.getElementById("submit");
            if(search.length >= 3) {
                submit.style.cssText += "background-color: green; color: white;";
                submit.disabled = false;
            }
        }
    </script>
    
</header>