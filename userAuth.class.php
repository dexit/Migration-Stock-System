<?php

//User authentication class, for reusable authentication against PVX username and password
//Check bottom of page for example of initializing
//Open this page to see a working example. (for now, obviously)
// REMEBER ABOUT GEORGIs FIX REGRDING SessionEXPIRE
class userAuth{

	//set class properties (variables)
	private $servername;
	private $db_username;
	private $db_password;
	private $db_name;
	
	//After how long should the user session expire in minutes (default set, can me changed with the setter method)
	private $sessionExpireAfter = 30;
	
	private $pdo;
	
	private $username;
	private $user_password;
	private $pvx_session_id;
	
	private $errorMessage;
	
	//CONSTRUCTOR function fires every time the class is initialised
	function __construct($servername, $db_username, $db_password, $db_name) {
		session_start();
	
		$this->servername = $servername;
		$this->db_username = $db_username;
		$this->db_password = $db_password;
		$this->db_name = $db_name;
		
		try{
			$this->pdo = new PDO("mysql:host=$this->servername;dbname=$this->db_name", $this->db_username, $this->db_password);
			// set the PDO error mode to exception == //Dunno if you actually need this Rihards, but will keep
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function login($username, $user_password) {
		$this->username = $username;
		$this->user_password = base64_encode($user_password);
		
		if ($this->pvxAuthenticate() == true) {
			
			try{
				$sql = "SELECT id, username, role_id, skin FROM user_auth WHERE username = :username";
				$query = $this->pdo->prepare($sql);
				$query->execute( array(":username" => $username) );
				$user_data = $query->fetch();
			}catch(PDOException $e) {
				echo "Selecting username exception : " . $e->getMessage();
			}
			
			if ($query->rowCount() == 1) {
				$_SESSION["username"] = $username;  
				$_SESSION["session"] = $this->pvx_session_id;
				$_SESSION["id"] = $user_data["id"];
				$_SESSION["role_id"] = $user_data["role_id"];
				$_SESSION['last_action'] = time();
				$_SESSION['skin'] = $user_data['skin'];
				return true;
			}else {
				$this->errorMessage = "This username/password does not exist in database.";
				return false;
			}
			
		}else {
			$this->errorMessage = "This username/password does not exist in PVX.";
			return false;
		}
	}
	
	private function pvxAuthenticate() {
		//Authenticate in PVX. 
		//If the pvx authentication fails, the error message will be specific (no such user in PVX). Please think if we want to show that information or not.
		
		//Config
		$ns = "http://www.peoplevox.net/";
		$clientid = "rng2744"; 					//this could also be set by a setter function, but for now it's okay

		// SOAP config
		$socket_context = stream_context_create(array('http' => array('protocol_version'  => 1.0)));
		// New SOAP CLIENT with options set
		$client = new SoapClient("http://wms.peoplevox.net/$clientid/resources/integrationservicev4.asmx?WSDL", array('exceptions' => 0,'stream_context' => $socket_context,'trace' => 1)); 

		// Building SOAP envelope for PVX AUTH
		$params = array("clientId"=>$clientid,"username"=>$this->username,"password"=>$this->user_password);
		
		// Try PVX Auth
		$start = $client->Authenticate($params);
		if (is_soap_fault($start)) {
			// If soap error, show message
			trigger_error("SOAP Fault: (faultcode: {$start->faultcode}, faultstring: {$start->faultstring})", E_USER_ERROR);
			return false;
			
		} else {
			// Get SOAP response Detail field
			$response = $start->AuthenticateResult->Detail;
			
			if ($response == "System : Security:Invalid Username or Password") {
				return false;
				
			}else {
				$response_explode = explode(",",$response);
				
				$this->pvx_session_id = $response_explode[1];
				return true;
			}
		}
	}
	
	public function userIsLogged() {
		//Cheks if the user is logged in. First looks if the session has expired, if not checks 


		if (isset($_SESSION["username"], $_SESSION["session"])) {
				
			if ($this->sessionExpired()) {
				$this->logOut();
				$this->errorMessage = "Your session has expired. Please login again.";
				return false;
			}else {
				return true;
			}
		}else {
			return false;
		}
		
		
	}
	
	private function sessionExpired() {
		 
		//Check to see if our "last action" session
		//variable has been set.
		if(isset($_SESSION['last_action'])){
			
			//Figure out how many seconds have passed
			//since the user was last active.
			$secondsInactive = time() - $_SESSION['last_action'];
			
			//Convert our minutes into seconds.
			$expireAfterSeconds = $this->sessionExpireAfter * 60;
			
			//Check to see if they have been inactive for too long.
			if($secondsInactive >= $expireAfterSeconds){
				//User has been inactive for too long. Return true, because session has expired.
				return true;
			}else {
				//session has not expired
				return false;
			}
			
		}	 

	}
	
	//Just a setter for flexibility, can change the time of session expiration in the go
	public function setSessionExpireAfter($sessionExpireAfter) {
		$this->sessionExpireAfter = $sessionExpireAfter;
	}
	
	//Set the last action to the current timestamp. 
	//This method will probably need to be called every time a user makes any kind of action?
	//Otherwise the session will expire in a certain time after the login, no matter if the user has been using the app.
	public function setLastAction() {
		$_SESSION['last_action'] = time();
	}
	
	//get the role of the currently logged in user
	public function getRoleId() {
		return $_SESSION["role_id"];
	}
	
	//unset all sessions
	public function logOut() {
		$_SESSION = array();
		session_destroy();
	}
	
	//Method for error handling. Can be called anywhere, so class doesn't need to echo anything, otherwise no control over err. messaging.
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	//get the skin the user has set as preference
	//NOTE: in future this should be a gerUserSettings method, which should select from userSettings table and return an array!
	public function getUserSkin() {
		return $_SESSION['skin'];
	}
	
	public function setUserSkin($skin) {
		try{
			$sql = "UPDATE user_auth SET skin = :skin WHERE id = :user_id";
			$query = $this->pdo->prepare($sql);
			$query->execute( array("skin" => $skin, "user_id" => $_SESSION["id"] ) );
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$_SESSION['skin'] = $skin;
	}
	
	public function createUser() {
		//for admins
	}
	
	public function deleteUser() {
		//for admins
	}

}
?>
