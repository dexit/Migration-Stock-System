<?php

        //PICKING

		$location_from = trim($_POST['location_from']); 
		$location_from = getFullLocation($location_from); //adding R. if not already there
		
		$pallet_id = trim($_POST['pallet_id']);
		
		$location_to = trim($_POST['location_to']);
		$location_to = getFullLocation($location_to);
		
		$pullback_number = trim($_POST['pullback_number']);
		
		$pdoCrudObj = new pdoCrud();
		//validations required: is this pallet on location_from; does it exists?; is location_to a real location.
		
		//GET THIS PALLET'S LOCATION ID
		$sql = "SELECT location_id FROM migration_stock_pallets WHERE pallet_id = :pallet_id";
		$pallet_location_id = $pdoCrudObj->selectColumn($sql, array('pallet_id' => $pallet_id) );
		
		if ($pdoCrudObj->getRowCount() < 1) {
			echo 'No pallet with id ' . $pallet_id . ' found.';
		}else {
			//GET THE PALLET'S LOCATION NAME BY ID
			$sql = "SELECT location_name FROM migration_stock_locations WHERE id = :pallet_location_id";
			$pallet_location_name = $pdoCrudObj->selectColumn($sql, array('pallet_location_id' => $pallet_location_id));
			
			if ($pallet_location_name != $location_from) {
				echo 'Pallet ' . $pallet_id . ' not found on location ' . $location_from;
			}else {
				
				//CHECK IF LOCATION_TO EXISTS
				$sql = "SELECT id FROM migration_stock_locations WHERE location_name = :location_to";
				$location_id = $pdoCrudObj->selectColumn($sql, array('location_to' => $location_to));
				
				if ($pdoCrudObj->getRowCount() > 0) {
				    
				    //CHECK IF LOCATION_TO IS PICK LOCATION
					$check_if_pick = checkIfPickLoc($location_to);
					
					if ($check_if_pick == true) {
						
						//ADD TO THE MOVEMENTS LOG ////////////
						//first select the values of that pallet, then add them
						$sql = "SELECT * FROM migration_stock_pallets WHERE pallet_id = :pallet_id";
						$pallet_values = $pdoCrudObj->select($sql, array('pallet_id' => $pallet_id) );
						
						$pallet_values = $pallet_values[0];
						
						
						$log_insert_values = array(
							'pullback_number' => $pullback_number,
							'pallet_sku' => $pallet_values['pallet_sku'], 
							'pallet_id' => $pallet_values['pallet_id'], 
							'location_from_id' => $pallet_values['location_id'],
							'location_to_id'   => $location_id,
							'movement_timestamp' => $datetime_today,
							'user' => $_SESSION['username'],
							'quantity' => $pallet_values['quantity'],
							'damaged'   => $pallet_values['damaged'],
							'ugly'  => $pallet_values['ugly'],
							'movement_type' => 'picking' 
							);
							
						$pdoCrudObj->insert('migration_stock_movements', $log_insert_values);
						//END ADDING TO LOG
    					////////////////////////////////////
						
						if ($pdoCrudObj->getRowCount() < 1) {
							echo 'Failed to insert into log! Please report this issue immediately.';
							if (!empty($pdoCrudObj->getErrors())) {
								$pdoCrudObj->showErrors();
							}
						}else {

    						
    						$values_to_update = array(
    								
    								'location_id' 		=> $location_id,
    								'added_by' 		=> $_SESSION['username'], 
    								'pallet_added'	=> $datetime_today,
    								'pullback_number'   => $pullback_number,
    							);
    						
    						//update the location		
    						$pdoCrudObj->update('migration_stock_pallets', $values_to_update, 'pallet_id = ' . $pallet_id);
    						
    						//if no rows were affected, pallet not found and show update errors if any
    						if($pdoCrudObj->getRowCount() == 0) {
    							echo 'No pallet with id ' . $pallet_id . ' found or pallet already on this location';
    							if (!empty($pdoCrudObj->getErrors())) {
    								$pdoCrudObj->showErrors();
    							}
    						}else {
    							$_SESSION['pullback_number'] = $pullback_number; //save the pullback number, so not to need input next time
    							
    							echo '<div class="alert alert-success">';
    							echo 'Pallet ' . $pallet_id . ' picked from ' . $location_from . ' into ' . $location_to;
    							echo '</div>';
    						}
    						
    						if (!empty($pdoCrudObj->getErrors())) {
    							$pdoCrudObj->showErrors();
    						}
						
						}
						
					
					}else {
						//location not pick location
						echo 'Location ' . $location_to . ' is not a pick location';
					}
				}else {
					echo 'Location ' . $location_to . 'does not exist';
					
					$pdoCrudObj->showErrors();
				}
			}			
		}