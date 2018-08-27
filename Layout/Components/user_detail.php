		<div class="row" style="margin-bottom: 10px;">
			<div class="col-md-12">
				
				<div class="card">
					<div class="card-header">User</div>
					<div class="card-body">
						Logged in as: <?php echo $_SESSION["username"]; ?> 
						<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?log_out">Log out</a>
						
					</div>
				</div>
				
			</div>
		</div>