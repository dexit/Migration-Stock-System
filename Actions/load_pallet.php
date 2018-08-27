<?php
        //LOADING
        
		$location_from = trim($_POST['location_from']); 
		$location_from = getFullLocation($location_from); //adding R. if not already there
		
		$pallet_id = trim($_POST['pallet_id']);
		
		$pullback_number = trim($_POST['pullback_number']);
		
		$pdoCrudObj = new pdoCrud();
		//validations required: is this pallet on location_from; does the pallet exists?; is location_from pick loc
		
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
				
				if ($pdoCrudObj->getRowCount() > 0) {
				    
				    //CHECK IF LOCATION_FROM IS PICK LOCATION
					$check_if_pick = checkIfPickLoc($location_from);
					
					if ($check_if_pick == true) {
						
						
						//REMOVE BOTTOM
						/*
						$values_to_update = array(
								
								'location_id' 		=> $location_id,
								'added_by' 		=> $_SESSION['username'], 
								'pallet_added'	=> $datetime_today,
								'pullback_number'   => $pullback_number,
							);
						
						//update the location		
						$pdoCrudObj->update('migration_stock_pallets', $values_to_update, 'pallet_id = ' . $pallet_id);
						*/
						//REMOVE TOP
						
						

						
						//ADD TO THE MOVEMENTS LOG ////////////
						//first select the values of that pallet, then add them
						$sql = "SELECT * FROM migration_stock_pallets WHERE pallet_id = :pallet_id";
						$pallet_values = $pdoCrudObj->select($sql, array('pallet_id' => $pallet_id) );
						
						$pallet_values = $pallet_values[0];
						
						$log_insert_values = array(
							'pullback_number' => $pullback_number,
							'pallet_sku' => $pallet_values['pallet_sku'], 
							'pallet_id' => $pallet_values['pallet_id'], 
							'location_to_id' => $pallet_values['location_id'],
							'movement_timestamp' => $datetime_today,
							'user' => $_SESSION['username'],
							'quantity' => $pallet_values['quantity'],
							'damaged'   => $pallet_values['damaged'],
							'ugly'  => $pallet_values['ugly'],
							'movement_type' => 'loading' 
							);
							
						$pdoCrudObj->insert('migration_stock_movements', $log_insert_values);
						////////////////////////////////////
						
						//if no rows were affected, pallet not found and show update errors if any
						if($pdoCrudObj->getRowCount() == 0) {
							echo 'FAILED LOG INSERT!';
							if (!empty($pdoCrudObj->getErrors())) {
								$pdoCrudObj->showErrors();
							}
						}else {
							//DELETE PALLET FROM TABLE (as in remove from the pick location)
							$pdoCrudObj->delete('migration_stock_pallets', 'pallet_id = ' . $pallet_id );
							
							if ($pdoCrudObj->getRowCount() > 0) {
								
								$_SESSION['pullback_number'] = $pullback_number; //save the pullback number, so not to need input next time
								$_SESSION['action'] = $_GET['action'];
								
								
								echo '<div class="alert alert-success">';
								echo 'Pallet ' . $pallet_id . ' loaded from ' . $location_from;
								echo '</div>';
							
							}else {
								echo 'FAILED PALLET DELETE';
								$pdoCrudObj->showErrors();
							}
						}
						
						if (!empty($pdoCrudObj->getErrors())) {
							$pdoCrudObj->showErrors();
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