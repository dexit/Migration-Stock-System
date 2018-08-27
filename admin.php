<?php

include 'config.php';

$pdoCrudObj = new pdoCrud();

$module = 'admin';

if (isset($_GET['page']) && $_GET['page'] == 'locations' || isset($_GET['location_id']) ) {
    $page = 'locations';
}else if (isset($_GET['page']) && $_GET['page'] == 'movements') {
    $page = 'movements';
}else {
    $page = 'pallets';
}

include 'Layout/header.php';
//$loaderObj->load(LAYOUT, 'header');

if (isset($_GET['page']) && $_GET['page'] == 'locations') {
    
    $sql = "SELECT * FROM migration_stock_locations";
    $locations_data = $pdoCrudObj->select($sql);
    
    //print_r($locations_data);
    
    $pdoCrudObj->showErrors();
    
	include COMPONENTS . '/display_locations_interface.php';
}else if (isset($_GET['location_id'])) {
    $location_id = $_GET['location_id'];
    
    //get the pallets in this location
    $sql = "SELECT * FROM migration_stock_pallets WHERE location_id = :location_id ORDER BY id DESC";
    $pallet_data = $pdoCrudObj->select($sql, array('location_id' => $location_id));
    
    //get the location name by id
    $sql = "SELECT location_name FROM migration_stock_locations WHERE id = :location_id";
    $location_name = $pdoCrudObj->selectColumn($sql, array('location_id' => $location_id));
    
	include COMPONENTS . '/display_location_interface.php';
	
}else if (isset($_GET['page']) && $_GET['page'] == 'movements') {
    
    $sql = "SELECT * FROM migration_stock_movements ORDER BY id DESC";
    $movements_data = $pdoCrudObj->select($sql);
    
    include COMPONENTS . '/display_movements_interface.php';
    
}else {
    
    $page = 'pallets';
    $sql = "SELECT * FROM migration_stock_pallets ORDER BY id DESC";
    $pallet_data = $pdoCrudObj->select($sql);
    
	include COMPONENTS . '/display_pallets_interface.php';
}

$loaderObj->load(LAYOUT, 'footer');
?>