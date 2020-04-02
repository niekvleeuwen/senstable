<?php

// DISABLED BY DEFAULT
exit();

require_once '../config/connect.php';

if(isset($_REQUEST['btn_register'])) 
{
	$username	= strip_tags($_REQUEST['txt_username']);	
	$email		= strip_tags($_REQUEST['txt_email']);		
	$password	= strip_tags($_REQUEST['txt_password']);	
		
	// check if the username textbox not empty 
	if(empty($username)){
		$errorMsg[]="Please enter username";	
	}
	// check if the email textbox not empty 
	else if(empty($email)){
		$errorMsg[]="Please enter email";	
	}
	// check for the proper email format 
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errorMsg[]="Please enter a valid email address";
	}
	// check if the passowrd textbox not empty
	else if(empty($password)){
		$errorMsg[]="Please enter password";	
	}
	// passowrd must be 6 characters
	else if(strlen($password) < 6){
		$errorMsg[] = "Password must be atleast 6 characters";	
	}
	else
	{	
		try
		{	
			$select_stmt=$pdo->prepare("SELECT username, email FROM users WHERE username=:uname OR email=:uemail");
			// execute query 
			$select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email)); 
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			
			// check condition username already exists 
			if($row["username"]==$username){
				$errorMsg[]="Sorry, deze gebruiker bestaat al!";
			}
			// check condition email already exists 
			else if($row["email"]==$email){
				$errorMsg[]="Sorry, deze email bestaat al!";	
			}
			// check if an error occured
			else if(!isset($errorMsg)) 
			{
				// encrypt password
				$new_password = password_hash($password, PASSWORD_DEFAULT); 
				
				$insert_stmt=$pdo->prepare("INSERT INTO users	(username,email,password) VALUES
																(:uname,:uemail,:upassword)"); 		// sql insert query					
				
				if($insert_stmt->execute(array(	':uname'	=>$username, 
												':uemail'	=>$email, 
												':upassword'=>$new_password))){
													
					$registerMsg="Het account is succesvol aangemaakt!"; 
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
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
    	<form  method="post" class="form-signin">
      		<img class="mb-4" src="img/user.svg" alt="" width="72" height="72">
      		<h1 class="h3 mb-3 font-weight-normal">Registreren</h1>
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
					if(isset($registerMsg))
					{
					?>
						<div class="alert alert-success">
							<strong><?php echo $registerMsg; ?></strong>
						</div>
					<?php
					}
			?>   
			<div class="form-group">
				<label for="inputUsername" class="sr-only">Gebruikersnaam:</label>
				<input type="text" id="inputUsername" name="txt_username" class="form-control" placeholder="Gebruikersnaam" required autofocus>

				<label for="inputEmail" class="sr-only">Email:</label>
				<input type="text" id="inputEmail" name="txt_email" class="form-control" placeholder="Email" required>

				<label for="inputPassword" class="sr-only">Wachtwoord:</label>
				<input type="password" name="txt_password" id="inputPassword" class="form-control" placeholder="Wachtwoord" required>
				<button class="btn btn-lg btn-primary btn-block" name="btn_register" type="submit">Registreren</button>
				<p class="mt-5 mb-3 text-muted">&copy; Senstable 2020</p>
			</div>
		</form>
  </body>
</html>
