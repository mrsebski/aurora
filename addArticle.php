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
$title = $body = "";
$status = 0;
$title_err = $body_err = "";
 
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


    // Validate credentials
    if(empty($title_err) && empty($body_err)){

        $sql = "INSERT INTO articles (title, body, user_id, status) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_title, $param_body, $param_user_id, $param_status);
            
            // Set parameters
            $param_title = $title;
            $param_body = $body;
            $param_user_id = $_SESSION['id'];
            $param_status = $status;
               
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
?>



    <div class="container">
    <div class="row justify-content-center mt-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add article</div>

                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">                    
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
                               <textarea style="white-space: pre-wrap;" name="body" rows=11 cols=50 maxlength=10000 required ></textarea>

                                <span class="help-block"><?php echo $body_err; ?></span>
                            </div>
                            
                        </div>

                        <div class="col-md-6 offset-md-4 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1">

                                    <label class="form-check-label" for="remember">
                                        Public article
                                    </label>
                            </div>
                        </div>


                        <!-- <div class="col-md-6 offset-md-4 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="seen" id="seen" value="1">

                                    <label class="form-check-label" for="remember">
                                        Seen only for logedin
                                    </label>
                                </div>
                            </div> -->
                        

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">

                                <button type="submit" class="btn btn-primary">
                                    Add article
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