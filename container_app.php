<?php

include 'config.php';

if ($pvxAuthObj->userIsLogged() == false) {
	
	if (isset($_POST["login"])) {
		$username = $_POST["username"];
		$user_password = $_POST["user_password"];
		$pvxAuthObj->login($username, $user_password);
	}
	
}else {
	
	//IF ATTEMPT TO LOG OUT
	if (isset($_GET["log_out"])) {
		$pvxAuthObj->logOut();
		header("Location: ".$_SERVER["PHP_SELF"]);
	}
	
	//ATTEMPT TO REMOVE LOAD NUMBER FROM SESSION
	if (isset($_GET['remove_load_number'])) {
	    unset($_SESSION['load_number']);
	    header('Location: ' . $_SERVER['PHP_SELF']);
	}
	
	if (isset($_GET['remove_pullback_number'])) {
	    unset($_SESSION['pullback_number']);
	    header('Location: ' . $_SERVER['PHP_SELF'] . '?action=picking');
	}

	$datetime_today = date("d/m/Y H:i");

	//IF ATTEMPT TO ADD PALLET
	if (isset($_POST["add_pallet"])) {
        include ACTIONS . '/add_pallet.php';

	}
	
	//MOVING A PALLET IS ATTEMPTED
	if (isset($_POST['move_pallet'])) {
        include ACTIONS . '/move_pallet.php';
	}
	
	//PICKING A PALLET IS ATTEMPTED
	if (isset($_POST['pick_pallet'])) {
        include ACTIONS . '/pick_pallet.php';
		
	}
	
	//PICKING A PALLET IS ATTEMPTED
	if (isset($_POST['load_pallet'])) {
        include ACTIONS . '/load_pallet.php';
		
	}
	
	
}


//VISUALS::
$module = 'android';
$page = 'android_app';

include 'Layout/header.php';
//$loaderObj->load(LAYOUT, 'header');

if ($pvxAuthObj->userIsLogged()) {
		
	$loaderObj->load(COMPONENTS, 'user_detail');
	
	
    if (isset($_GET['action']) && $_GET['action'] == 'move') {
		//ACTION INTERFACE IS MOVING	
		$loaderObj->load(COMPONENTS, 'move_pallet_interface');

    }else if ((isset($_GET['action']) && $_GET['action'] == 'picking')) {
		//ACTION INTERFACE IS PICKING
		$loaderObj->load(COMPONENTS, 'pick_pallet_interface');
		
    }else if ((isset($_GET['action']) && $_GET['action'] == 'loading')) {
		//ACTION INTERFACE IS LOADING
		$loaderObj->load(COMPONENTS, 'load_pallet_interface');

	}else {
		//ACTION INTERFACE IS ADDING	
		//include COMPONENTS . '/add_pallet_interface';
		$loaderObj->load(COMPONENTS, 'add_pallet_interface');
	} ?>
        
    </div>
	
	<?php
	}else {
		$loaderObj->load(COMPONENTS, 'login_form');
	}
	?>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>