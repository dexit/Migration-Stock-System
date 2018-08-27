<?php

class pdoCrud {

	//DATABASE VARIABLES
	private $servername = "localhost";
	private $database = "pvx_stats";
	private $db_username = "pvx_user";
	private $db_password = "pvxstats";
	
	private $pdo;
	
	private $rowCount;
	
	private $errors = array();

	function __construct() {
		$this->connectToDatabase();

	}
	
	//Set the db info (if default db info changes only -- otherwise, fires automatically when class initialized)
	public function setDatabase($servername, $database, $db_username, $db_password) {
		$this->servername = $servername;
		$this->database = $database;
		$this->db_username = $db_username;
		$this->db_password = $db_password;
	}
	
	//Connect to database (only needed if you set db info different than the default)
	public function connectToDatabase() {
		try {
			$this->pdo = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->db_username, $this->db_password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException $e){
			$this->errors[] = "Database connection error: " . $e->getMessage();
		}	
	}
	
	//Select from db
	public function select($sql, $values = array()) {
		
		try{
			$query = $this->pdo->prepare($sql);
			$query->execute($values);
			
			$result = $query->fetchAll();
			
			$this->rowCount = $query->rowCount();
			
			return $result;
		}catch (PDOException $e) {
			$this->errors[] = "Database select error: " . $e->getMessage();
			return false;
		}
	}
	
	//Select single row from db
	public function selectColumn($sql, $values = array()) {
		
		try{
			$query = $this->pdo->prepare($sql);
			$query->execute($values);
			
			$result = $query->fetchColumn();
			
			$this->rowCount = $query->rowCount();
			
			return $result;
		}catch (PDOException $e) {
		    echo 'Error: ' . $e->getMessage();
			$this->errors[] = "Database select error: " . $e->getMessage();
			return false;
		}
	}
	
	//Insert into db
	public function insert($table, $data) {
		
		$columns = implode(',', array_keys($data) );
		$values = ':' . implode (', :', array_keys($data) );

		try {
			$sql = "INSERT INTO $table ($columns) VALUES ($values )";
			$query = $this->pdo->prepare($sql);
			
			foreach ($data as $key => $value) {
				$query->bindValue(":$key", $value);
			}
		
			$result = $query->execute();
			
			if ($result == true) {
				return true;
			}else {
				return false;
			}
		}catch (PDOException $e) {
			$this->errors[] = "$table insert error: " . $e->getMessage();
			return false;
		}
		
	}
	
	//Update db
	public function update($table, $data, $where) {
		
		$params = "";
		$counter = 0;
		foreach ($data as $key => $value) {
			$counter++;
			$params .= "$key = :$key";
			$params .= ($counter < count($data) ? "," : "");
			
		}
		
		try{
			$sql = "UPDATE $table SET $params";
			$sql .= (!empty($where) ? " WHERE " . $where : "");
			
			$query = $this->pdo->prepare($sql);
			
			foreach ($data as $key => $value) {
				$query->bindValue(":$key", $value);
			}
			
			$query->execute();	
			
			$this->rowCount = $query->rowCount();
			
			return true;
		}catch (PDOException $e) {
			$this->errors[] = "Update $table error: " . $e->getMessage();
			return false;
		}
		
	}
	
	//Delete from db
	public function delete($table, $where, $limit = 1){
		try{
			$sql = "DELETE FROM $table WHERE $where LIMIT $limit";
			$query = $this->pdo->prepare($sql);
			$query->execute();
			
			return true;
		}catch (PDOException $e) {
			$this->errors[] = "Delete from $table error: " . $e->getMessage();
			return false;
		}
	}
	
	//Delete everything from table
	public function emptyTable($table){
		try{
			$sql = "DELETE FROM $table";
			$query = $this->pdo->prepare($sql);
			$query->execute();
			
			return true;
		}catch (PDOException $e) {
			$this->errors[] = "Delete from $table error: " . $e->getMessage();
			return false;
		}
	}
	
	public function getRowCount() {
		return $this->rowCount;
	}
	
	//Get the errors array
	public function getErrors() {
		return $this->errors;
	}
	
	//Quickhand method for displaying errors
	public function showErrors() {
		foreach ($this->errors as $error) {
			echo $error . "\r\n";
		}
	}

}

/*
$pdoCrudObj = new pdoCrud();
$select = $pdoCrudObj->select("SELECT * FROM testing" );
print_r($select);

echo $pdoCrudObj->getRowCount();

//$pdoCrudObj->insert("testing", array("one" => "oneval", "two" => "twovalue", "three" => 55) );

//$pdoCrudObj->update("testing", array("one" => "onevalone", "two" => "twovaltwo", "three" => 57), "id = 2" );

//$pdoCrudObj->delete("testing", "id = 2" );

$pdoCrudObj->showErrors();




$pdoCrudObj = new pdoCrud();
$sql = "SELECT * FROM testing WHERE two = :tag_id";
$data = $pdoCrudObj->select($sql, array(":tag_id" => "CE025AF6"));

foreach ($data as $user) {
    echo $user["one"];
} 


echo $pdoCrudObj->getRowCount();

if ($pdoCrudObj->getRowCount() == 1) {
    echo "y";   
}

*/



