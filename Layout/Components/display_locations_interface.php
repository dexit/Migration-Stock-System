

	
    <div class="row">
        <div class="col-md-12">
		
			<div class="card">
				<div class="card-header">
					Locations
					

                    <div class="dropdown float-right" >
                      <button type="button" class="btn btn-primary dropdown-toggle btn-block " data-toggle="dropdown">
                        Barcodes <i class="fas fa-cog"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" style="width:270px;">
                        
                            <a style="white-space: normal;" class="dropdown-item" href="#">Print empty locations</a>
                            <a style="white-space: normal;" class="dropdown-item" href="#">Print occupied locations</a>
                        
                      </div>
                    </div>
					
					
				</div>
				<div class="card-body">

					<table class="table" id="locations_table">
						<thead>
							<th>
								#
							</th>
							<th>
								Location name
							</th>
							<th>
								Status
							</th>
							<th>
								No. of pallets
							</th>
						</thead>
						<tbody>
							<?php
							$counter = 0;
							foreach ($locations_data as $data) {
								$counter++;
								
								$sql = "SELECT id FROM migration_stock_pallets WHERE location_id = :location_id";
								$pallet = $pdoCrudObj->select($sql, array('location_id' => $data['id']));
								
								$pdoCrudObj->showErrors();
								?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td>
										<a href="admin.php?location_id=<?php echo $data['id']; ?>" >
											<?php echo $data['location_name']; ?>
										</a>
									</td>
									<td><?php echo ($pdoCrudObj->getRowCount() > 0) ? '<span class="text-danger">Occupied</span>': '<span class="text-success">Empty</span>'; ?></td>
									<td><?php echo $pdoCrudObj->getRowCount(); ?></td>
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