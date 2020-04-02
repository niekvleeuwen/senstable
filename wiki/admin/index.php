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
        $username	=strip_tags($_REQUEST["usernamePost"]);	
        $password	=strip_tags($_REQUEST["passwordPost"]);			
            
        // check if "username/email" or password textbox not empty 
        if(empty($username) || empty($password)){						
            $errorMsg[]="Vul een gebruikersnaam en wachtwoord in!";	
        }else{
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
                            $errorMsg[]="De gebruikersnaam en/of het wachtwoord is onjuist.";
                        }
                    }
                    else
                    {
                        $errorMsg[]="De gebruikersnaam en/of het wachtwoord is onjuist.";
                    }
                }
                else
                {
                    $errorMsg[]="De gebruikersnaam en/of het wachtwoord is onjuist.";
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
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin | Senstable</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin">
      <img class="mb-4" src="img/user.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Inloggen</h1>
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
      <label for="inputUsername" class="sr-only">Gebruikersnaam:</label>
      <input type="text" id="inputUsername" name="usernamePost" class="form-control" placeholder="Gebruikersnaam" required autofocus>
      <label for="inputPassword" class="sr-only">Wachtwoord:</label>
      <input type="password" name="passwordPost" id="inputPassword" class="form-control" placeholder="Wachtwoord" required>
      <button class="btn btn-lg btn-primary btn-block" name="btn_login" type="submit">Inloggen</button>
      <p class="mt-5 mb-3 text-muted">&copy; Senstable 2020</p>
    </form>
  </body>
</html>
