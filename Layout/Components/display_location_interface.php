
    
    <div class="row">
        <div class="col-md-12">
            
			<div class="card">
				<div class="card-header">
					<?php echo $location_name; ?>
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
								Location
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
						</thead>
						<tbody>
							<?php
							$counter = 0;
							foreach ($pallet_data as $data) {
								$counter++;
								

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
										<?php //echo $location_name; ?>
										<?php echo $data['location_name']; ?>
									</td>
									
									<td>
										<?php echo $data['quantity']; ?>
									</td>
									
									<td>
										<?php echo $data['added_by']; ?>
									</td>
									
									<td>
										<?php echo $data['pallet_added']; ?>
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
