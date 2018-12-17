<?php
    
    // ##**##** START search form processor ##**##**
    // import search keyword(s) from search form in includes/header.php
    require_once("conn/connPlay.php");
    $search = $_POST['search']; // at least 3 chars worth of text here
    $search = mysqli_real_escape_string($conn, $search);
    // This is the "R" in CRUD -- reading records
    // search using LIKE (inludes) since "chess" is not likely to be an exact match for hobbies
    $query = "SELECT * FROM members, images
    WHERE members.IDmbr = images.foreignID AND images.catID = 3
    AND (gamesPlayed LIKE '%$search%'
    OR aboutMe LIKE '%$search%'
    OR firstName LIKE '%$search%'
    OR lastName LIKE '%$search%'
    OR user LIKE '%$search%') 
    ORDER BY user ASC";
    
    $result = mysqli_query($conn, $query);

    // test to see if you got any results at all
    // hand the first result off to the $row array
//    $row = mysqli_fetch_array($result);
//    $mainPic = $row['imgName'];
//
//    $msg = "User: " . $row['user'] . " Hobbies: " . $row['hobbies'] . " Main Pic File Name: " . $row['imgName'];

//    echo $msg;

    // ##**##** END search form processor ##**##**

?>

<?php
    $title = "Search Results:";
?>

<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<main>

    <h1><?php echo $title; ?></h1>
    
<!--    <h2><?php// echo $msg; ?></h2>-->
    
    <table id="searchTable" width="80%" border="1px" cellpadding="15px" align="center" >
        
        <tr>
            <th colspan="2">Members matching your search for <?php echo $search; ?></th>
        </tr>
       
        <!-- a PHP WHILE LOOP TO OUTPUT ALL $row results -->
        <?php while($row = mysqli_fetch_array($result))
        {
        ?>
        <tr>
            <td>
              <?php
                $mainPic = $row['imgName'];
                // if main profile pic is the generic Coming Soon pic
                if($mainPic == 'pic-coming-soon.jpg') {
                    
                    // the Coming Soon Pic is in main images folder
                    $imgPath = 'images/' . $mainPic;
                    
                } else { // main pic not default/generic Coming Soon
                    
                    // the user's Profile pic is in their own folder
                    $imgPath = 'members/' . $row['user'] . '/images/' . $mainPic;
                }
            
                // output the user profile image
                echo '<img src="' . $imgPath . '"  
                width="200px" height="auto" id="mainPic">';

            ?>
                
                
            </td>
            <td>
                  
                <?php 
                  
                  echo "<p>Username: <a href='profile.php?searchID=". $row['IDmbr'] . "' style='text-decoration: underline;  color:blue;'>" . $row['user'] .  "</a> Name: " . $row['firstName'] . " " 
                             . $row['lastName'] . "</p><p>Games Played: " . $row['gamesPlayed'] . "</p><p>About Me: " . $row['aboutMe'] . "</p>";
                
                
                ?>
      
            </td>
        </tr>
        <?php 
        }
        ?>
        <!-- end PHP WHILE LOOP -->
    
    </table>

</main>

<script>
    // don't show the search box on the search results page
    document.getElementById("search-form").style.display = "none";
</script>

<?php include 'includes/footer.php'; ?>







