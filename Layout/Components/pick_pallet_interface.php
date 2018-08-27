<?php
?>
		<!-- INPUT (FOR ADDING DATA) -->
        <div class="row" style="width:100%; margin:0px auto;">
        
            <form class="form-horizontal" action="" method="POST" autocomplete="off" style="width:100%;">
            	<div class="form-control">
            	
            	    <div style="">
                		<label for="pullback_number_input">Pullback number:</label>
                		<input id="pullback_number_input" class="form-control input" type="text" name="pullback_number"  autocomplete="off" required
                		
                		    <?php echo (isset($_SESSION['pullback_number']) ? '' : 'autofocus'); ?>
                		    
                		    value="<?php echo (isset($_SESSION['pullback_number']) ? $_SESSION['pullback_number'] : ''); ?>"
                		    <?php echo (isset($_SESSION['pullback_number']) ? 'readonly' : ''); ?>
                		>
                		    
                		<p class="small float-right"> <a href="<?php $_SERVER['PHP_SELF']; ?>?remove_picking_pullback_number">Change pullback</a></p>
                        <div style="clear:both;"></div>
            		</div>

                	<label for="location_from_input">Location from:</label>
                	<input id="location_from_input" class="form-control input" type="text" name="location_from"  autocomplete="off" autofocus required>
                	<br />
            		
					<label for="pallet_id_input">Pallet ID:</label>
					<input id="pallet_id_input" class="form-control input" type="text" name="pallet_id"  autocomplete="off" required>
                	<br />
					
					<label for="location_to_input">Pick location:</label>
					<input id="location_to_input" class="form-control input" type="text" name="location_to"  autocomplete="off" required>
                	<br />
            		
            		<div style="margin-top: 10px;">
            		    <input class="form-control btn btn-primary" type="submit" name="pick_pallet" value="Pick pallet" style="height:55px; width:50%;  float: right;"/>
            		</div>
					
					<div style="clear:both;"></div>
					
            	</div>
            </form>
            
        </div>

