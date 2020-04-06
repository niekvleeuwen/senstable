<?php
	require_once '../config/connect.php';

	session_start();
	// check if the user has acces to the home page
	if(!isset($_SESSION['user_login']))	
	{
		header("location: index.php");
	}

	if(isset($_REQUEST['addSensorButton']))	
    {
		$name               = strip_tags($_REQUEST['sensName']);
		$short_description  = strip_tags($_REQUEST['sensShortDescription']);
		$serial_number      = strip_tags($_REQUEST['sensSerialNumber']);
		$wiki               = strip_tags($_REQUEST['sensWiki']);
		$code               = strip_tags($_REQUEST['sensCode']);

		$target_dir = "img/sensors/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check === false) {
			$errorMsg[]="File is not an image.";
			$uploadOk = 0;
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			$errorMsg[]="Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$errorMsg[]="Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$errorMsg[]="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$errorMsg[]="Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$diagramPath = $target_dir . basename( $_FILES["fileToUpload"]["name"]);
				
				$sql = "INSERT INTO sensors (name, short_description, serial_number, diagram, wiki, code) VALUES (:name, :short_description, :serial_number, :diagram, :wiki, :code)";

				$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

				$stmt = $pdo->prepare($sql);

				$stmt->bindValue(':name', $name, PDO::PARAM_STR);
				$stmt->bindValue(':short_description', $short_description, PDO::PARAM_STR);
				$stmt->bindValue(':serial_number', $serial_number, PDO::PARAM_STR);
				$stmt->bindValue(':wiki', $name, PDO::PARAM_STR);
				$stmt->bindValue(':code', $name, PDO::PARAM_STR);
				$stmt->bindValue(':diagram', $diagramPath, PDO::PARAM_STR);

				$result = $stmt->execute();

				if ($result) {
					$succesMsg="De sensor is succesvol toegevoegd!"; 
				} else {
					$errorMsg[]="Sorry, er is een fout opgetreden.";
				}
			} else {
				$errorMsg[]="Sorry, er is een fout opgetreden.";
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
		<title>Dashboard | Senstable</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">
  	</head>
    <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="home.php">Senstable</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Zoeken" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="logout.php">Uitloggen</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="home.php">
                  <span data-feather="file"></span>
                  Sensors
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="code"></span>
                  Code
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="droplet"></span>
                  Ontwerpen
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <h2>Sensors</h2>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Naam</th>
                <th scope="col">Beschrijving</th>
                <th scope="col">Serienummer</th>
                <th scope="col">Datum toegevoegd</th>
                </tr>
            </thead>
            <tbody id="table"></tbody>
            </table>
          </div>
          <div class="row">
			<div class="col-sm-6">
				<h4>Sensor toevoegen</h4>
				<hr>
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
							<strong><?php echo $succesMsg; ?></strong>
						</div>
					<?php
					}
				?>   
				<form method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="sensName">Naam</label>
						<input type="text" class="form-control" id="sensName" name="sensName" placeholder="Naam van de sensor" required>
					</div>
					<div class="form-group">
						<label for="sensSerialNumber">Serienummer</label>
						<input type="text" class="form-control" id="sensSerialNumber" name="sensSerialNumber" placeholder="Serienummer (bijv. HC-SR04)" required>
					</div>
					<div class="form-group">
						<label for="fileToUpload">Diagram</label>
						<input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
					</div>
					<div class="form-group">
						<label for="sensShortDescription">Korte beschrijving</label>
						<input type="text" class="form-control" id="sensShortDescription" name="sensShortDescription" placeholder="Korte beschrijving (max 100 karakters)" required>
					</div>
					<div class="form-group">
						<label for="sensWiki1">Beschrijving</label>
						<textarea name="sensWiki" class="form-control" id="sensWiki1" rows="5" placeholder="Beschrijving over de sensor" required></textarea>
					</div>
					<div class="form-group">
						<label for="sensCode1">Voorbeeld code</label>
						<textarea name="sensCode" class="form-control" id="sensCode1" rows="5" placeholder="Voorbeeld code" required></textarea>
					</div>
					<button type="submit" name="addSensorButton" class="btn btn-primary">Toevoegen</button>
				</form>
			</div>
			<div class="col-sm-6">
				<h4>Sensor verwijderen</h4>
				<hr>
				<form method="post" action="delete.php" enctype="multipart/form-data">
					<div class="form-group">
						<label for="exampleFormControlSelect1">Sensor</label>
						<select class="form-control" id="exampleFormControlSelect1">
						<!--- Hier met php alle sensoren ophalen en displayen -->
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						</select>
					</div>
					<button type="submit" name="addSensorButton" class="btn btn-primary">Verwijderen</button>
				</form>
          	</div>
		  <br />
        </main>
      </div>
    </div>

    <!-- JS
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="js/home.js"></script>							
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
  </body>
</html>
