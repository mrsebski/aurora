
<?php 
// Initialize the session
session_start();

include('view/header.php');
include('view/menu.php') ;
require_once "config.php";
        
// Check if user Logged
if((isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) 
   AND 
   (isset($_SESSION["id"]) || $_SESSION["id"] === true))
{
    $user_id = $_SESSION["id"];

    
    $sql = "SELECT art.title, art.body, art.id, art.user_id, us.username
		FROM articles AS art 
		LEFT JOIN users AS us
		ON art.user_id = us.id 
		WHERE art.status = true 
		ORDER BY art.updated_at DESC;";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
               
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    //article template

                    if ($row['user_id'] == $user_id) {
                    	echo '
                        <div class="col-md-4">
                        <div class="card bg-light mb-3" style="max-width: 18rem;">
                          
                          <div class="card-body">
                            <h5 class="card-title"><a href="showArticle.php?id='.$row['id'].'"> '.$row['title'].'</a></h5>
                            <p class="card-text">'.$row['body'].'</p>

                          </div>

                          <div class="card-footer d-flex justify-content-center">
                                    <div class="col-5">
                                    <a href="editArticle.php?edit_id='.$row['id'].'" class="btn btn-outline-success">Edit</a>
                                    </div>
                                    <div class="col-5">
                                    <a href="articles.php?article_id='.$row['id'].'" class="btn btn-outline-danger">Remove</a>
                                    </div>
                                    
                          </div>
                          
                        </div>
                                </div>
                    ';
                    }else{
                    	echo '
						<div class="col-md-4">
							<div class="card border-primary mb-3" style="max-width: 18rem;">
								<div class="card-body t">
								    <h5 class="card-title"><a href="showArticle.php?id='.$row['id'].'">'.$row['title'].'</a></h5>
								    <p class="card-text">'.$row['body'].'</p>
								</div>
								<div class="card-footer bg-transparent">Author: '.$row['username'].'</div>
							</div>
						</div>
						            ';
                    }
                    
                    





                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);


}

else

{
	// If user is not loged in
	// Select all articles with status = true

        $sql = "SELECT art.title, art.body, art.id, art.user_id, us.username
        FROM articles AS art 
        LEFT JOIN users AS us
        ON art.user_id = us.id 
        WHERE art.status = true 
        ORDER BY art.updated_at DESC;";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters       
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    //article template

                    echo '
                    <div class="col-md-4">
                        <div class="card border-primary mb-3" style="max-width: 18rem;">
                  
                        <div class="card-body t">
                            <h5 class="card-title"> <a href="showArticle.php?id='.$row['id'].'"> '.$row['title'].'</a></h5>
                            <p class="card-text">'.$row['body'].'</p>
                        </div>
                        <div class="card-footer bg-transparent">Author: '.$row['username'].'</div>
                        </div>
                    </div>
                    ';
                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);

}

?>

      </div>

    </div>


		</body>
	</html>