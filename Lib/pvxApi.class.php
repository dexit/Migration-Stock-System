<?php

//Authentication class
class pvxApi{
	private $ns = "http://www.peoplevox.net/";
	private $client_id;
	private $username;
	private $password;
	
	private $client;
	
	private $DateTime = null;
	
	function __construct($username, $password, $user_id, $client_id = "rng2744") {
		$this->username = $username;
		$this->password = base64_encode($password);
		$this->client_id = $client_id;
		
		$socket_context = stream_context_create(array('http' => array('protocol_version' => 1.0)));
		$this->client = new SoapClient("http://wms.peoplevox.net/".$this->client_id."/resources/integrationservicev4.asmx?WSDL", array('exceptions' => 0, 'stream_context' => $socket_context, 'trace' => 1));
		
		$params = array("clientId" => $this->client_id, "username" => $this->username, "password" => $this->password);
		$start = $this->client->Authenticate($params);
	
		if (is_soap_fault($start)){
			trigger_error("SOAP Fault: (faultcode: {$start->faultcode}, faultstring: {$start->faultstring})", E_USER_ERROR);
			print "<br />";
		} else{
			$response = $start->AuthenticateResult->Detail;
			$response_explode = explode(",", $response);
			$sessionid = $response_explode[1];
		
			//Body of the Soap Header.
			$headerbody = array('UserId' =>$user_id, 'ClientId' => $this->client_id, 'SessionId' => $sessionid);
			//Create Soap Header
			$header = new SOAPHeader($this->ns, 'UserSessionCredentials', $headerbody);
			//set the Headers of Soap Client.
			$this->client->__setSoapHeaders($header);
		}
	}
	
	public function getClient(){
		return $this->client;
	}
		//DATE AND TIME METHODS
	public function setTimezone($timezone) {
		if ($this->DateTime == null) {
			$this->DateTime = new DateTime();
		}
		$this->DateTime->setTimezone(new DateTimeZone($timezone));
	}
	public function getTodayDate() {
		if ($this->DateTime == null) {
			$this->DateTime = new DateTime();
		}
		$date_today = $this->DateTime->format('Y,m,d');
		return $date_today;
	}
	public function getTodayDateAndTime(){
		if ($this->DateTime == null) {
			$this->DateTime = new DateTime();
		}
		$datetime_today = $this->DateTime->format('Y,m,d,h,i,s');
		return $datetime_today;
	}
}


//Report class
class pvxReport {
	
	private $client;
	
	private $pageNo = 0;
	private $searchClause = '';
	private $itemsPerPage = 0;
	private $filterClause = '';
	private $orderBy = '';
	private $columns = '';
	
	private $reportDetail;
	private $reportCount;
	
	
	function __construct($client) {
		
		$this->client = $client;

	}
	
	public function getReport($template = "Item movement history", $searchClause = "", $filterClause = "", $columns = "", $orderBy = "", $pageNum = "0", $itemsPerPage = "0"){
		
		$body = array( 'TemplateName' => $template,
			'PageNo' => $this->pageNo,
			'ItemsPerPage' => $this->itemsPerPage,
			'SearchClause' => $this->searchClause,
			'FilterClause' => $this->filterClause,
			'OrderBy' => $this->orderBy,
			'Columns' => $this->columns);
			
		$params = array('getReportRequest' => $body);
		$str = $this->client->GetReportData($params);
		
		if (is_soap_fault($str)) {
			trigger_error("SOAP Fault: (faultcode: {$str->faultcode}, faultstring: {$str->faultstring})", E_USER_ERROR);
		}
		
		$reportObject = $str->GetReportDataResult;
		
		$this->reportDetail = $reportObject->Detail;
		$this->reportCount = $reportObject->TotalCount;
		
		return $reportObject;
	}
	
	public function getDetailTable(){
		 $table = "<table>";
		 $rows = str_getcsv($this->reportDetail, "\n");
		 foreach($rows as &$row){
			$table .= "<tr>";
			$cells = str_getcsv($row);
			foreach($cells as &$cell){
			  $table .= "<td>$cell</td>";
			}
			$table .= "</tr>";
		}
		$table .= "</table>";
		return $table;
	}
	
	public function getTotalCount() {
		return $this->reportCount;
	}
	public function getDetail() {
		return $this->reportDetail;
	}
	
	//filter setters
	public function setPageNo($pageNo) {
		$this->pageNo = $pageNo;
	}
	public function setSearchClause($searchClause) {
		$this->searchClause = $searchClause;
	}
	public function setItemsPerPage($itemsPerPage) {
		$this->itemsPerPage = $itemsPerPage;
	}
	public function setFilterClause($filterClause) {
		$this->filterClause = $filterClause;
	}
	public function setOrderBy($orderBy) {
		$this->orderBy = $orderBy;
	}
	public function setColumns($columns) {
		$this->columns = $columns;
	}
}


//NEW WAY USE EXAMPLE
//$api = new pvxAPI("ggeorgiev", "goper", "1858");											-- AUTHENTICATE FIRST
//$client = $api->getClient();																-- GET THE CLIENT VARIABLE

//$reportObj = new pvxReport($client);														-- PASS THE CLIENT VARIABLE TO EVERY NEW REPORT OBJECT
//$reportObj->setSearchClause('[Sales order no.].EndsWith("-N") AND [Status].Equals("Picked")');
//$reportObj->getReport("Sales orders by status");											-- ALSO RETURNS THE GetReportDataResult!
//echo $reportObj->getDetail();																-- RETURNS DETAIL PROPERTY | CAN ALSO GET PROPERTY Detail DIRECTLY FROM getReport() SAVED INTO VARIABLE
//echo $reportObj->getDetailTable();														-- RETURNS DETAIL PROPERTY DISPLAYED INTO A TABLE
//echo $reportObj->getTotalCount()															-- RETURNS TOTAL COUNT PROPERTY | CAN ALSO GET PROPERTY TotalCount DIRECTLY FROM getReport() SAVED INTO VARIABLE

//OLD WAY USE EXAMPLE:
//$api = new pvxReport("ggeorgiev", "goper", "1858");									 	-- TO CONNECT WITH USERNAME, PASSWORD AND USERID (CLIENT ID IS HARDCODED)
//$api->setSearchClause('[Sales order no.].EndsWith("-N") AND [Status].Equals("Picked")');	-- NEED TO USE ALL THE FILTER SETTERS BEFORE RUNNING getReport()
//$api->setItemsPerPage(0);																	-- ALL SETTER METHODS CORESPONDING WITH FILTER NAME /// PROBLEM: NEXT REPORT WITH GET THE PREVIOUS FILTERS IF NEW NOT SET
//$report = $api->getReport("Sales orders by status");
//echo $api->getDetailTable(); 																-- RETURNS DETAIL PROPERTY DISPLAYED INTO A TABLE
//echo 'totalcount: ' . $report->TotalCount; 												-- GET TOTAL COUNT DIRECTLY FROM RESPONSE, OR USE $api->getTotalCount() FUNCTION


//FILTERS EXAMPLE:
//ALL RDD Today filter: [Requested delivery date]=DateTime('. $date_today .',00,00,00) AND ![Status].Equals("Despatched") AND ![Status].Equals("Cancelled") AND ![Status].Equals("New") AND ![Status].Equals("Partially allocated")
//All RDD filter: [Requested delivery date]=DateTime(2018,04,29,00,00,00) AND ![Status].Equals("Despatched") AND ![Status].Equals("Cancelled") AND ![Status].Equals("New") AND ![Status].Equals("Partially allocated")
//RDD filter: [Requested delivery date]=DateTime(2018,04,26,01,00,00)
//All_NextDays_filter: [Sales order no.].EndsWith("-N") AND ![Status].Equals("Despatched") AND ![Status].Equals("Cancelled")
//Order by filter: [Date timestamp] desc
//Item movement history columns: [User],[Item code],[Date timestamp],[From],[To],[Comments]