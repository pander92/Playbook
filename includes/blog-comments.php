<!-- ###***### ###***### ###***### ###***###
###***### START BLOG COMMENTS SECTION ###***### 
###***### INCLUDED IN blog.php ###***### --> 

<?php

    // query the comments table for all comments forr the current Main Blog (which is always the most recently posted blog). We also need to query the members table so we can identify the commenter by username and profile pic
    $queryComments = "SELECT * FROM comments, members, images
    WHERE comments.mbrID = members.IDmbr 
    AND comments.blogID = '$IDblog'
    AND comments.mbrID = images.foreignID
    AND isMainPic = 1
    AND catID = 3
    ORDER BY commentTime DESC";

    $resultComments = mysqli_query($conn, $queryComments);

?>



<section id="comments-section" style="background-color:aqua; min-height:200px; padding:1rem; clear:both; margin-top:1rem">

    <h2>
        <i class="fas fa-comments"></i>
        Comments for blog #<?php echo $IDblog; ?>
    </h2>
    
    <div id="comment-box" style="background-color:#FFF; border:2px solid #888; padding:1rem;">
        <textarea name="new-comment" id="new-comment" style="width:95%; min-height:50px; padding:1rem;" placeholder="Leave a comment"></textarea>
        
        <button type="button" onclick="postAJAXComment()" style="font-size:1rem; margin:0.5rem; padding:0.25rem 0.75rem;">
        POST
        </button>
        
    </div>
    
    <div id="comments" style="background-color:azure; min-height:100px; padding:1rem">
        
        <?php 
        
            while($rowCom = mysqli_fetch_array($resultComments)) { 
                $comm = '<div id="comment-' . $rowCom['IDcomment'] . '"
                                style="border:2px solid #888; 
                                        margin: 0.5rem 0; 
                                        padding:0.5rem;
                                        min-height:110px;
                                        background-color:beige">';
                
                $comm .= '<div style="float:left; width:15%; margin:2%;">';
                
                $comm .='<img src="members/' . $rowCom['user'] . '/images/' . $rowCom['imgName'] . '" style="width:60px; height: 60px; border-radius:30px; border:2px solid #888; margin-right:2rem"></div>';
                
                $comm .= '<div style="float:right; width:75%"><p>';
                
                $comm .= '<span style="color:maroon; font-weight:bold; 
                        font-size:1rem">' . $rowCom['user'] . '</span><br/>'; 
                
                $comm .= $rowCom['comment']  . '</p></div></div>';
                
                $comm .= '<div style="clear:both; height:2px"></div>';
                
                echo $comm;
        
            } 
        ?>
        
    </div><!-- close div id="comments" -->

</section>

<script>
    function postAJAXComment() {
        var newCommentBox = document.getElementById('new-comment');
        var newComment = newCommentBox.value;
        newComment = encodeURI(newComment);
        
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if(xhr.status == 200 && xhr.readyState == 4) {
                document.getElementById('comments').innerHTML = xhr.responseText;
            }
        }
        var urlVars = "comment=" + newComment;
        urlVars += "&mbrID=8";
        urlVars += "&blogID=3";
        xhr.open("GET", "save-comment.php?" + urlVars, true);
        xhr.send();
        newCommentBox.value = "";
    }
</script>

<!-- ###***### ###***### ###***### ###***###
###***### END BLOG COMMENTS SECTION ###***### 
###***### ###***### ###***### ###***### --> 