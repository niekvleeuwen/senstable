<?php
	require_once '../config/connect.php';

	session_start();
	//check unauthorize user not access in "home.php" page
	if(!isset($_SESSION['user_login']))	
	{
		header("location: index.php");
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
                        <li class="active"><a href="index.php">Admin</a></li>
                    </ul>
                </div>
            </div>
        </nav>
		<div class="wrapper">
			<div class="container">	
				<div class="row">
					<div class="col-lg-12">
						<center>
							<h2>
								<?php
									$id = $_SESSION['user_login'];
									
									$select_stmt = $pdo->prepare("SELECT * FROM users WHERE user_id=:uid");
									$select_stmt->execute(array(":uid"=>$id));
						
									$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
									
									if(isset($_SESSION['user_login']))
									{
									?>
										Welkom
									<?php
											echo $row['username'];
									}
								?>
							</h2>
							<a href="logout.php">Uitloggen</a>
						</center>
					</div>
				</div>
				<hr />
			    <div class="row">
					<h3>Sensoren</h3>
					<table class="table">
						<thead>
							<tr>
							<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Beschrijving</th>
							<th scope="col">Serienummer</th>
							<th scope="col">Datum toegevoegd</th>
							</tr>
						</thead>
						<tbody id="table">
							
						</tbody>
					</table>
				</div>
			</div>	
		</div>	
		<script src="js/home.js"></script>							
	</body>
</html>