<?php		
        $load_number = trim($_POST['load_number']);
		$pallet_sku = trim($_POST["pallet_sku"]);
		$pallet_id = trim($_POST["pallet_id"]);
		$location_name = trim($_POST["location_name"]);
		$location_name = getFullLocation($location_name);
		$quantity = trim($_POST['quantity']);

		$username = $_SESSION['username'];
		
		$damaged_pallet = (isset($_POST['damaged_check']) ? 'true' : 'false');
		$ugly_pallet = (isset($_POST['ugly_check']) ? 'true' : 'false');
		
		$pdoCrudObj = new pdoCrud();
		
        //get location id
        $sql = "SELECT id FROM migration_stock_locations WHERE location_name = :location_name";
        $location_id = $pdoCrudObj->selectColumn($sql, array('location_name' => $location_name) );
		
		//YOU NEED TO CHECK IF LOCATION EXISTS WHICH YOU'RE NOT DOING RIGHT NOW
		
        if (empty($pdoCrudObj->getErrors()) ) {
            
            $insert_values = array(
                'load_number' => $load_number,
                'pallet_sku' => $pallet_sku, 
                'pallet_id' => $pallet_id, 
                'location_id' => $location_id,
                'pallet_added' => $datetime_today,
                'added_by' => $username,
                'quantity' => $quantity,
                'damaged'   => $damaged_pallet,
                'ugly'  => $ugly_pallet
                
                );
            
            //VALIDATION CHECKS
            if (is_numeric($quantity) == false) {
		        echo '<div class="alert alert-danger">';
		        echo $quantity . ' is not a real quantity value!';
		        echo '</div>';                
            }else {
                
				//CHECK IF SAME PALLET ID ALREADY INSERTED
				$sql = "SELECT pallet_id FROM migration_stock_pallets WHERE pallet_id = :pallet_id ";
				$pdoCrudObj->selectColumn($sql, array(':pallet_id' => $pallet_id));
				
				if ($pdoCrudObj->getRowCount() == 0) {
					
					$pdoCrudObj->insert('migration_stock_pallets', $insert_values);
					$_SESSION['load_number'] = $load_number;
					
					//ADD TO THE MOVEMENTS LOG
					$log_insert_values = array(
						'load_number' => $load_number,
						'pallet_sku' => $pallet_sku, 
						'pallet_id' => $pallet_id, 
						'location_to_id' => $location_id,
						'movement_timestamp' => $datetime_today,
						'user' => $username,
						'quantity' => $quantity,
						'damaged'   => $damaged_pallet,
						'ugly'  => $ugly_pallet,
						'movement_type' => 'adding' 
						);
						
					$pdoCrudObj->insert('migration_stock_movements', $log_insert_values);
					
					if (empty($pdoCrudObj->getErrors())) {
						echo '<div class="alert alert-success">';
						echo 'Pallet ' . $pallet_id . ' added into ' . $location_name;
						echo '</div>';
					}else {
						$pdoCrudObj->showErrors();
					}
				
				}else{
					echo 'A pallet with this id has already been added!';
				}
            }
        }else {
            $pdoCrudObj->showErrors();
        }