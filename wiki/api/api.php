<?php
class API {
  	// Properties
	private $pdo;

	// Constructor
	function __construct() {
		// connect to database
		require_once './../../../config/config.php';
		$this->pdo = new PDO(
			"mysql:host=" . $app['db']['servername'] . ";dbname=" . $app['db']['database'],
			$app['db']['username'],
			$app['db']['password']
		);
	}

	// Methods
	public function setHeaders() {
		header('Content-type:application/json;charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

	public function authenticate($token) {
		$sql = "SELECT * FROM users,tokens WHERE tokens.token = :token";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':token', $token, PDO::PARAM_STR);
		$result = $stmt->execute();
		if ($stmt->fetch(PDO::FETCH_ASSOC) > 0) {
			return true;
		}
		return false;
	}

	public function getAuthErrorMessage(){
		$data = [
			'error' => 'U moet opnieuw inloggen!',
		];
		return $data;
	}

	public function sendQuery($query, array $binds)
	{
		$stmt = $this->pdo->prepare($query);
		return $stmt->execute($binds);
	}
}
?>