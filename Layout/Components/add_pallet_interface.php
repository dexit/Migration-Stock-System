		<!-- INPUT (FOR ADDING DATA) -->
        <div class="row" style="width:100%; margin:0px auto;">
        
            <form class="form-horizontal" action="" method="POST" autocomplete="off" style="width:100%;">
            	<div class="form-control">
            	
            	    <div style="">
                		<label for="load_number_input">Load number:</label>
                		<input id="load_number_input" class="form-control input" type="text" name="load_number"  autocomplete="off" required 
                		
                		    <?php echo (isset($_SESSION['load_number']) ? '' : 'autofocus'); ?>
                		    value="<?php echo (isset($_SESSION['load_number']) ? $_SESSION['load_number'] : ''); ?>"
                		    <?php echo (isset($_SESSION['load_number']) ? 'readonly' : ''); ?>
                		>
                		    
                		<p class="small float-right"> <a href="<?php $_SERVER['PHP_SELF']; ?>?remove_load_number">Change load</a></p>
                        <div style="clear:both;"></div>
            		</div>
            	
            	    <div style="float: left; width: 47%;">
                		<label for="pallet_sku_input">Pallet SKU:</label>
                		<input id="pallet_sku_input" class="form-control input" type="text" name="pallet_sku"  autocomplete="off" autofocus required>
                		<br />
            		</div>
            		
            		<div style="float:right; width:50%;";>
                		<label for="pallet_id_input">Pallet ID:</label>
                		<input id="pallet_id_input" class="form-control input" type="text" name="pallet_id"  autocomplete="off" autofocus required>
                		<br />
            		</div>
            		
            		
            		
            		<div style="float:left;width:47%;">
                		<label for="pallet_location_input">Location:</label>
                		<input id="pallet_location_input" class="form-control input" type="text" name="location_name" autocomplete="off" autofocus required>
                		<br />
            		</div>
            		
            		<div style="float:right;width:50%;">
                		<label for="item_quantity_input">Qantity:</label>
                		<input id="item_quantity_input" class="form-control input" type="text" name="quantity" autocomplete="off" autofocus required>
                		<br />
            		</div>
            		
            		<!-- style="height:10%; font-size:2.0rem;" -->
            		
            		<div>
            		    <label for="damaged_check">Damaged</label>
            		    <input type="checkbox" style="width: 20px; height: 20px; margin-right:5px;" name="damaged_check" id="damaged_check" />
            		     
            		    <label for="ugly_check">Ugly</label>
            		    <input type="checkbox" style="width: 20px; height: 20px;" name="ugly_check" id="ugly_check" />            		    
            		</div>
            		
         		
            		
            		<div style="margin-top: 10px;">
            		    <input class="form-control btn btn-primary" type="submit" name="add_pallet" value="Add pallet" style="height:55px; width:50%;  float: right;"/>
            		</div>
            		
            		<div style="clear:both;"></div>
            	</div>
            </form>
            
        </div>