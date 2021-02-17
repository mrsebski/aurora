<?php

 
// Include config file
require_once "config.php";

include('view/header.php');
include('view/menu.php');
 
// Define variables and initialize with empty values


if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if ((isset($_GET["id"]))) 
    {
        $art_id = $_GET['id'];

        // $sql = "SELECT * FROM articles WHERE id = ? AND status = ?";
        $sql = "SELECT art.title, art.body, art.id, art.user_id, us.username
        FROM articles AS art 
        LEFT JOIN users AS us
        ON art.user_id = us.id 
        WHERE art.id = ? 
        AND art.status = ? 
        ORDER BY art.updated_at LIMIT 1";

    
        if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "is",  $param_article_id, $param_status);
        
        // Set parameters
        
        $param_article_id = $art_id;
        $param_status = true;
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                $id = $edit_id;
                
                $title = $row['title'];
                $body = $row['body'];
                $author = $row['username'];

                   
            }else{
                header("location: articles.php");
            }
        } 

    }
    }

}
?>

    <div class="container">
    <div class="row justify-content-center mt-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo $title; ?></div>

                <div class="card-body">
                    <p><?php echo nl2br($body); ?></p>
                </div>

                <div class="card-footer">
                    Author:
                    <?php
                        echo $author;
                    ?>
                </div>


            </div>
        </div>
    </div>
</div>

   
</body>
</html>