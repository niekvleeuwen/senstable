<?php
    require_once '../config/connect.php';
    session_start();

    //check condition user login not direct back to index.php page
    if(isset($_SESSION["user_login"]))
    {
        header("location: home.php");
    }

    if(isset($_REQUEST['btn_login']))	
    {
        $username	=strip_tags($_REQUEST["txt_username_email"]);
        $email		=strip_tags($_REQUEST["txt_username_email"]);	
        $password	=strip_tags($_REQUEST["txt_password"]);			
            
        // check if "username/email" textbox not empty 
        if(empty($username)){						
            $errorMsg[]="please enter username or email";	
        }
        // check if "username/email" textbox not empty
        else if(empty($email)){
            $errorMsg[]="please enter username or email";	 
        }
        // check if "passowrd" textbox not empty 
        else if(empty($password)){
            $errorMsg[]="please enter password";	
        }
        else
        {
            try
            {
                $select_stmt=$pdo->prepare("SELECT * FROM users WHERE username=:uname OR email=:uemail");
                $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));
                $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
                
                // check condition database record greater zero after continue
                if($select_stmt->rowCount() > 0)	
                {
                    if($username==$row["username"] OR $email==$row["email"])
                    {
                        if(password_verify($password, $row["password"])) 
                        {
                            $_SESSION["user_login"] = $row["user_id"];		
                            header("Location: home.php");
                            die();		
                        }
                        else
                        {
                            $errorMsg[]="wrong password";
                        }
                    }
                    else
                    {
                        $errorMsg[]="wrong username or email";
                    }
                }
                else
                {
                    $errorMsg[]="wrong username or email";
                }
            }
            catch(PDOException $e)
            {
                $e->getMessage();
            }		
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
        <title>Admin | Senstable</title>
                
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="js/jquery-1.12.4-jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>	
    </head>
	<body>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../index.html">Senstable</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="../index.html">Terug naar home</a></li>
                    </ul>
                </div>
            </div>
        </nav>
	    <div class="wrapper">	
	        <div class="container">
		        <div class="col-lg-12">
                    <?php
                    if(isset($errorMsg))
                    {
                        foreach($errorMsg as $error)
                        {
                        ?>
                            <div class="alert alert-danger">
                                <strong><?php echo $error; ?></strong>
                            </div>
                        <?php
                        }
                    }
                    if(isset($loginMsg))
                    {
                    ?>
                        <div class="alert alert-success">
                            <strong><?php echo $loginMsg; ?></strong>
                        </div>
                    <?php
                    }
                    ?>   
			        <center>
                        <h2>Login</h2>
                    </center>
			        <form method="post" class="form-horizontal">
				        <div class="form-group">
				            <label class="col-sm-3 control-label">Gebruikersnaam</label>
				            <div class="col-sm-6">
                                <input type="text" name="txt_username_email" class="form-control" placeholder="Voer uw gebruikersnaam in" />
                            </div>
				        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Wachtwoord</label>
                            <div class="col-sm-6">
                                <input type="password" name="txt_password" class="form-control" placeholder="Voer uw wachtwoord in" />
                            </div>
                        </div>
				        <div class="form-group">
				            <div class="col-sm-offset-3 col-sm-9 m-t-15">
				                <input type="submit" name="btn_login" class="btn btn-success" value="Login">
				            </div>
				        </div>
			        </form>
		        </div>
	        </div>	
	    </div>									
	</body>
</html>