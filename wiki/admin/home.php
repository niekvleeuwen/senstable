<?php
	session_start();

	// check if the user has acces to the home page
	if(!isset($_SESSION['user_login'])){
		include("index.php");
		exit();
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
		<style><?php include 'css/dashboard.css'; ?></style>
		<style><?php include 'css/bootstrap.min.css'; ?></style>
		<!-- <style><?php //include 'fontawesome/css/all.min.css'; ?></style> -->
  	</head>
    <body onload="loadSensorTable()">
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
            <table class="table table-striped table-sm" id="sensorTable">
				<thead>
					<tr>
						<th scope="col" class="sens-id">#</th>
						<th scope="col">Naam</th>
						<th scope="col">Beschrijving</th>
						<th scope="col">Serienummer</th>
						<th scope="col">Datum toegevoegd</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody id="table"></tbody>
			</table>
			<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="add-sensor">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<rect opacity="0" x="0" y="0" width="28" height="28"></rect>
					<circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"></circle>
					<path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"></path>
				</g>
			</svg>
		  </div>
			<div class="form-popup" id="popup-form">
				<div class="row col-sm-6 d-flex ml-2">
					<h4>Sensor toevoegen</h4>
					<hr>
					<form method="post" enctype="multipart/form-data">
						<br>
						<div class="form-group">
							<input type="text" class="form-control" id="sensName" name="sensName" placeholder="Naam van de sensor" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="sensSerialNumber" name="sensSerialNumber" placeholder="Serienummer (bijv. HC-SR04)" required>
						</div>
						<br>
						<div class="form-group">
							<input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
						</div>
						<br>
						<div class="form-group">
							<textarea class="form-control" id="sensShortDescription" rows="2" name="sensShortDescription" placeholder="Korte beschrijving (max 100 karakters)" required></textarea>
						</div>
						<div class="form-group">
							<textarea name="sensWiki" class="form-control" id="sensWiki" rows="5" placeholder="Beschrijving over de sensor" required></textarea>
						</div>
						<br>
						<div class="form-group">
							<textarea name="sensCode" class="form-control" id="sensCode" rows="5" placeholder="Voorbeeld code" required></textarea>
						</div>
						<button type="button" name="addSensorButton" class="btn btn-primary mb-1" onclick="addSensor()">Toevoegen</button>
						<button type="button" name="cancel" class="btn btn-cancel mb-1" onclick="closeForm()">Annuleren</button>
					</form>
				</div>
			</div>
		  <br />
        </main>
      </div>
    </div>

    <!-- JS
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script><?php include 'js/home.js'; ?></script>						
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js" type="text/javascript"></script>
    <script>
      feather.replace()
    </script>
  </body>
</html>
