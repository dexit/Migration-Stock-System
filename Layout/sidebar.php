
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>
					<b>M</b> igration <br /> 
					<b>S</b> tock <br />
					<b>S</b> ystem
				</h3>
            </div>

            <ul class="list-unstyled components">
			
				<?php if ($module == 'admin') { ?>
					
					<!-- ADMIN MENU  -->
					<li <?php echo ($page == 'pallets' ? 'class="active"' : ''); ?>>
						<a href="admin.php">
							<i class="fas fa-pallet"></i>
							Pallets
						</a>
					</li>
					
					<li <?php echo ($page == 'locations' ? 'class="active"' : ''); ?>>

						<?php 
						
						if (isset($_GET['location_id'])) { 
							//get the location name to show in submenu
							$pdoCrudObj = new pdoCrud();
							$sql = "SELECT location_name FROM migration_stock_locations WHERE id = :location_id";
							$location_name = $pdoCrudObj->selectColumn($sql, array('location_id' => $_GET['location_id']));
							?>
							
							<a href="admin.php?page=locations">
								<i class="fas fa-barcode"></i>
								Locations
							</a>
							<ul class="list-unstyled" id="pageSubmenu">
								<li>
									<a href="#" class="submenu_item_active" style="color:#6d7fcc;"><?php echo $location_name; ?></a>
								</li>
								
							</ul>
							
						<?php }else { ?>
						
							<a href="admin.php?page=locations">
								<i class="fas fa-barcode"></i>
								Locations
							</a>				
						
						<?php } ?>
					</li>                
					
					<li <?php echo ($page == 'movements' ? 'class="active"' : ''); ?>>
						<a href="admin.php?page=movements">
							<i class="fas fa-history"></i>
							Movements
						</a>
					</li>
				
				
				<?php }else { ?>
					<!-- ANDROID APP MENU  -->
					
					<li <?php echo ($action == '' ? 'class="active"' : ''); ?>>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>">
							<i class="fas fa-plus"></i>
							Add
						</a>
					</li>
					
					<li <?php echo ($action == 'move' ? 'class="active"' : ''); ?>>
						
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=move">
							<i class="fas fa-people-carry"></i>
								Move
						</a>
						
					</li>                
					
					<li <?php echo ($action == 'picking' ? 'class="active"' : ''); ?>>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=picking">
							<i class="fas fa-chevron-circle-down"></i>
							Pick
						</a>
					</li>

					<li <?php echo ($action == 'loading' ? 'class="active"' : ''); ?>>
						<a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=loading">
							<i class="fas fa-truck-loading"></i>
							Load
						</a>
					</li>					
				
				<?php } ?>
            </ul>
			
			<?php if ($module == 'android') { ?>
            <ul class="list-unstyled components" style="border: none; padding:0px;">
				<li <?php echo ($action == 'history' ? 'class="active"' : ''); ?>>
					<a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=history">

						History
					</a>
				</li>	
			</ul>
			<?php } ?>
			
        </nav>