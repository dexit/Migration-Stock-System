<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Migration stock</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
	
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" />

	

	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
	
	
	<link rel="stylesheet" type="text/css" href="Css/sidebar.css"/>
    
    <style>
        .body{padding:0px;margin: 0px;}
		.loader {
			position: fixed;
			left: 50%;
			top: 50%;
			width: 100%;
			height: 100%;
			z-index: 9999;
		}
		#dim { 
			display: block;
			position: fixed;
			left: 0;
			top: 0; 
			background:grey;
			width:100%; 
			height:100%; 
			background-color: rgba(0,0,0,0.5);
			z-index: 2;
		}
		
		#top_navbar{
		    margin-bottom: 25px;
		}
		
		.dropdown-menu-right {
			right: 0;
			left: auto;
		}

    </style>
    
    <script>
	$(window).on( 'beforeunload', function() {
				
		$("#dim").show();
		$(".loader").fadeIn();
		$("body").css("overflow", "hidden");
	});    
	
	$(document).ready(function() {
		$("#dim").fadeOut();
		$(".loader").hide();
		
		/*
		$("#pallets_table").DataTable( {
			dom: 'lBfrtip',
			"lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
			"buttons": [
				{ "extend": 'excel', "text":'Excel',"className": 'btn btn-success btn-xs' }
			],
		
		} );
		*/
		
		
		
		$("#pallets_table").dataTable({  
			dom: 'lBfrtip',
			buttons: [
				{ extend: 'excel', className: 'btn btn-primary' },
			]
		});
		
		$("#locations_table").dataTable({  
			dom: 'lBfrtip',
			buttons: [
				{ extend: 'excel', className: 'btn btn-primary' },
			]
		});
		
		$(document).on("click", ".delete_row", function(e) {
			$confirm_delete = confirm("Are you sure you want to delete this row?");
			
			if ($confirm_delete == true) {
			
			}else {
				e.preventDefault();
			}
		});
		
		
		//Sidebar
		//$("#sidebar").mCustomScrollbar({
            //theme: "minimal"
        //});

        $('#dismiss, .overlay').on('click', function () {
            $('#sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').addClass('active');
            $('.overlay').addClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

	});
		
	</script>
</head>
<body class="body">

    <div class="overlay"></div>

	<!-- Overlay and loader  -->
    <div id="dim"></div>
    <div class="loader">
    	<div class="fa fa-spinner fa-spin" style="font-size: 30px;"></div>
    </div>


    <div id="">
	<nav class="navbar navbar-light bg-light border-bottom" id="top_navbar">
         
         <!-- LOGO -->
         <a class="navbar-brand" href="<?php echo ($module == admin ? 'admin.php' : 'container_app.php'); ?> ">
            <b>M</b>igration <b>S</b>tock <b>S</b>ystem
        </a>
         
        
        <!-- Sidebar expander -->
        <div class="mr-auto">
            <button type="button" id="sidebarCollapse" class="btn btn-info btn-lg" >
				<i class="fas fa-align-left"></i>
                <span></span>
            </button>
        </div>
            
             
            <!-- DROPDOWN -->
			<!--
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>">
					<i class="fas fa-plus"></i>
					Add
				</a>
				
                <a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=move">
					<i class="fas fa-people-carry"></i>
					Move
				</a>
                <a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=picking">
					<i class="fas fa-chevron-circle-down"></i>
					Pick
				</a>
                <a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=loading">
					<i class="fas fa-truck-loading"></i>
					Load
				</a>
				
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=history">History</a>
              </div>
            </div>
			-->
            <!-- END DROPDOWN -->
         
    </nav>
    
    <?php
        include 'sidebar.php';
    ?>

	<div class="container-fluid" >
	