<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
// Include config file
require_once "config.php";

include('view/header.php');
include('view/menu.php');



if( $_SERVER["REQUEST_METHOD"] == "GET")
{
    if (
        (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) 
        AND 
        (isset($_SESSION["id"]) || $_SESSION["id"] === true)
        AND 
        (isset($_GET['article_id']))
        ) {
        $user_id = $_SESSION["id"];
        $article_id = $_GET['article_id'];

        echo $user_id .'<br>';
        echo $article_id;

        $sql = "SELECT * FROM articles WHERE id = ? AND user_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii",  $param_article_id, $param_user_id);
        
        // Set parameters
        $param_user_id = $user_id;
        $param_article_id = $article_id;

        if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                

                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    echo "usuwamy!!!!";
                    $remove = "DELETE FROM articles WHERE id = ? AND user_id = ?";

                    if($stmt_del = mysqli_prepare($link, $remove)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt_del, "ii",  $param_article_id, $param_user_id);
                        
                        // Set parameters
                        $param_user_id = $user_id;
                        $param_article_id = $article_id;

                        mysqli_stmt_execute($stmt_del);

                         mysqli_stmt_close($stmt_del);

                         header("location: articles.php");


                        
                    }

                }else{
                    header("location: articles.php");
                }
            }

    }

        // article_id is only a number
        //article_id -> remove everything exapt [0-9]

        


        
        }
}

 

 
if(
    (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) 
    AND 
    (isset($_SESSION["id"]) || $_SESSION["id"] === true)
){
    $user_id = $_SESSION["id"];
    // Prepare a select statement
    $sql = "SELECT * FROM articles WHERE user_id = ? ORDER BY updated_at DESC";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_user_id);
        
        // Set parameters
        $param_user_id = $user_id;
        
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
                        <div class="card border-dark mb-3" style="max-width: 18rem;">
                          
                          <div class="card-body text-dark">
                            <h5 class="card-title">'.$row['title'].'</h5>
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





                }
            } else{
                echo "<p>No articles yet!</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);


    }

?>
 

    





   
</body>
</html>