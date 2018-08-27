   
    <div class="row">
        <div class="col-md-12">
            
			<div class="card">
				<div class="card-header">
					Movements
				</div>
				<div class="card-body">
					<table class="table" id="pallets_table">
						<thead>
							<th>
								#
							</th>
							<th>
								Pallet id
							</th>
							<th>
								Pallet SKU
							</th>
							<th>
								Location from
							</th>
							<th>
							    Location to
							</th>
							<th>
								Quantity
							</th>
							<th>
								User
							</th>                
							<th>
								Datetime
							</th>
							<th>
								Ugly
							</th>
							<th>
								Damaged
							</th>
							<th>
								Load number
							</th>
							<th>
							    Movement type
							</th>
							<th>
							    Pullback number
							</th>
						</thead>
						<tbody>
							<?php
							$counter = 0;
							foreach ($movements_data as $data) {
								$counter++;
								
								$sql = "SELECT location_name FROM migration_stock_locations WHERE id = :location_id";
								$location_from_name = $pdoCrudObj->selectColumn($sql, array('location_id' => $data['location_from_id']));
								
								$sql = "SELECT location_name FROM migration_stock_locations WHERE id = :location_id";
								$location_to_name = $pdoCrudObj->selectColumn($sql, array('location_id' => $data['location_to_id']));
							?>
								<tr>
									<td>
										<?php echo $counter; ?>
									</td>
									<td>
										<?php echo $data['pallet_id']; ?>
									</td>
									
									<td>
										<?php echo $data['pallet_sku']; ?>
									</td>
									
									<td>
										<?php echo $location_from_name; ?>
									</td>
									
									<td>
									    <?php echo $location_to_name; ?>
									</td>
									
									<td>
										<?php echo $data['quantity']; ?>
									</td>
									
									<td>
										<?php echo $data['user']; ?>
									</td>
									
									<td>
										<?php echo $data['movement_timestamp']; ?>
									</td>
									
									<td>
										<?php echo ($data['ugly'] == 'true' ? '<span class="text-warning">Ugly</span>' : ''); ?>
									</td>
									
									<td>
										<?php echo ($data['damaged'] == 'true' ? '<span class="text-danger">Damaged</span>' : ''); ?>
									</td>
									
									<td>
										<?php echo $data['load_number']; ?>
									</td>
									
									<td>
										<?php echo $data['movement_type']; ?>
									</td>
									
									<td>
									    <?php echo $data['pullback_number']; ?>
									</td>
								</tr>
								
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
            
        </div>
    </div>