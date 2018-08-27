<?php
?>
		<!-- INPUT (FOR ADDING DATA) -->
        <div class="row" style="width:100%; margin:0px auto;">
        
            <form class="form-horizontal" action="" method="POST" autocomplete="off" style="width:100%;">
            	<div class="form-control">
            	
                	<label for="location_from_input">Location from:</label>
                	<input id="location_from_input" class="form-control input" type="text" name="location_from"  autocomplete="off" autofocus required>
                	<br />
            		
					<label for="pallet_id_input">Pallet ID:</label>
					<input id="pallet_id_input" class="form-control input" type="text" name="pallet_id"  autocomplete="off" required>
                	<br />
					
					<label for="location_to_input">Location to:</label>
					<input id="location_to_input" class="form-control input" type="text" name="location_to"  autocomplete="off" required>
                	<br />
            		
            		<div style="margin-top: 10px;">
            		    <input class="form-control btn btn-primary" type="submit" name="move_pallet" value="Move pallet" style="height:55px; width:50%;  float: right;"/>
            		</div>
					
					<div style="clear:both;"></div>
					
            	</div>
            </form>
            
        </div>

