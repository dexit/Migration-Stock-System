<?php

//Simple PVX authentication class, checks against PVX username and password
//Open this page to see a working example. (for now, obviously)

class pvxAuth{
	
	private $username;
	private $user_password;
	private $pvx_session_id;
	
	private $errorMessage = array();
	
	//CONSTRUCTOR function fires every time the class is initialised
	function __construct() {
		session_start();
	}
	
	public function login($username, $user_password) {
		$this->username = $username;
		$this->user_password = base64_encode($user_password);
		
		if ($this->pvxAuthenticate() == true) {
			
			$_SESSION["username"] = $username;  
			$_SESSION["session"] = $this->pvx_session_id;
			return true;
			
		}else {
			$this->errorMessage[] = "This username/password does not exist in PVX.";
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
			return true;
		}else {
			return false;
		}	
		
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

}
?>


<?php
//EXAMPLE BELOW
/*
$pvxAuthObject = new pvxAuth();

if (isset($_GET["logout"])) {
	$pvxAuthObject->logout();
	header("Location: " . $_SERVER["PHP_SELF"]);
}

$error = $pvxAuthObject->getErrorMessage();
echo $error;

if (isset($_POST["login"])) {

	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$pvxAuthObject->login($username, $password);
}

if ($pvxAuthObject->userIsLogged()) {
	echo 'yo ' . $_SESSION["username"];
	echo '<a href="' . $_SERVER["PHP_SELF"] . '?logout">Logout </a>';
}else {
?>

<form action="" method="POST">
	<p>
		Username: 
		<input type="text" name="username" />
	</p>
	<p>
		Password:
		<input type="password" name="password" />
	</p>
	<p>
		<input type="submit" value="Login" name="login" />
	</p>
		
</form>


<?php
}
*/
?>


