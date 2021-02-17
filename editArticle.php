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
 
// Define variables and initialize with empty values

 $title_err = $body_err = '';
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if title is empty
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter article title.";
    } else{
        $title = trim($_POST["title"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["body"]))){
        $body_err = "Please enter article body.";
    } else{
        $body = trim($_POST["body"]);
    }


    if(isset($_POST['status']))
    {      
        $status = 1;
    }

    if(isset($_POST['id']))
    {      
        $id = trim($_POST["id"]);
    }

    // Validate credentials
    if(empty($title_err) && empty($body_err)){

        $sql = "UPDATE articles 
        SET title = ?, body = ?, status = ?, updated_at = ?
        WHERE id = ? AND user_id = ?" ;
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisii",$param_title, $param_body, $param_status, $param_updated_at, $param_id, $param_user_id);
            
            // Set parameters
            $param_id = $id;
            $param_title = $title;
            $param_body = $body;
            $param_status = $status;
            $param_user_id = $_SESSION['id'];
            $param_updated_at = date('Y-m-d H:i:s');
   
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: articles.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    }
    // Close connection
    mysqli_close($link);
}

if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (
        (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true) 
        AND 
        (isset($_SESSION["id"]))
        AND 
        (isset($_GET["edit_id"]))
        ) 
    {
        $user_id = $_SESSION["id"];
        $edit_id = $_GET['edit_id'];

        
        $sql = "SELECT * FROM articles WHERE id = ? AND user_id = ?";
    
        if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii",  $param_article_id, $param_user_id);
        
        // Set parameters
        $param_user_id = $user_id;
        $param_article_id = $edit_id;

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                $id = $edit_id;
                
                $title = $row['title'];
                $body = $row['body'];

                $status = $row['status'];
                   
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
                <div class="card-header">Edit article</div>

                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">                    
                        <div class="form-group row <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Title </label>

                            <div class="col-md-6">
                                <input type="text" name="title" class="form-control" 
                                value="<?php echo $title; ?>"
                                required=""  
                                autofocus="">

                                <span class="help-block"><?php echo $title_err; ?></span>

                            </div>
                        </div>

                        <div class="form-group <?php echo (!empty($body_err)) ? 'has-error' : ''; ?> row">
                            <label for="body" class="col-md-4 col-form-label text-md-right">Body</label>

                            <div class="col-md-6">
                               <textarea style="white-space: pre-wrap;" name="body"  rows=11 cols=50 maxlength=10000 required>
                                   <?php echo $body; ?>
                               </textarea>

                                <span class="help-block"><?php echo $body_err; ?></span>
                            </div>
                            
                        </div>

                        <div class="col-md-6 offset-md-4 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="<?php echo $status; ?>"
                                    <?php if ($status === 1) echo 'checked' ?>>

                                    <label class="form-check-label" >
                                        Public article
                                    </label>
                                </div>
                        </div>                        

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">

                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>        

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

   
</body>
</html>