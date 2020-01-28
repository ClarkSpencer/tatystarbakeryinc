<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tatystar Bakery Inc. - Order Online</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/logo3.png">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
	<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	 <link href="./plugins/toastr/css/toastr.min.css" rel="stylesheet">

</head>



<?php
session_start();


if(isset($_POST['add'])){
	
	
	$stock = $_POST['stock'];
	$name = $_POST['name'];
	$price = $_POST['price'];
		
	
		if(!empty($stock) AND !empty($name)){
			$qty_input = $stock;
			$prod_code = $name;
			$con = mysqli_connect("localhost", "root", "", "shoppingcart_db");

				
					$row_array = array(
					"id" => $name ,
					"stock" => $stock,
					"price" => $price
					
					);
				
			
			$productByCode= $row_array;
			$productByCode["quantity"] = $qty_input;
			
			if(!empty($_SESSION["cart_item2"])){ //////UPDATES THE QUANTITY
				$inCart = false;
			
				foreach($_SESSION["cart_item2"] as $rowindex => $row){
					$current_code = $_SESSION["cart_item2"][$rowindex]["id"];
				
					if($productByCode["id"] == $current_code){
						$current_code;
						$_SESSION["cart_item2"][$rowindex]["stock"] +=
							$qty_input;
						$inCart = true;
					}
				}
				if (!$inCart){
					array_push($_SESSION["cart_item2"], $productByCode);////REMAIN ITEM
				}
			}else{
				$_SESSION["cart_item2"] = array($productByCode);
			}
		/////////////
	}else{
		/////////////
	}
	
}

//////////////////////////////



if(isset($_POST['emptycart'])){
	unset($_SESSION["cart_item2"]);
	unset($_SESSION["creation_decision3"]);
	
}
	
	/////////////////////////////////////////////////////////////////
	
	
	
if(isset($_POST["delete"]))  
{     
		if (!empty($_SESSION["cart_item2"])) {
			
			foreach ($_SESSION["cart_item2"] as $select => $_SESSION["array2"]) {
				
				if($_SESSION["array2"]["id"] == $_POST["id"])
				{
					
					unset($_SESSION["cart_item2"][$select]);
				
				}
			}
	}
 }

/////////////////////////////////////////////////////



if(isset($_POST['checkout'])){
	



	foreach($_SESSION["cart_item2"] as $item){
			$id2 = $item["id"];
			$stocks2 = $item["stock"];
			
			$search_query3 = "SELECT * FROM tbl_products WHERE name = '$id2'";
			$search3 = mysqli_query($connect, $search_query3);
			if(mysqli_num_rows($search3)>0)
			{		
					while($row3=mysqli_fetch_array($search3))
					{
					if( $row3['stock'] <  $stocks2) 
										{
											echo "<script>alert('Insufficient Stock to check out!');</script>";
											$_SESSION['creation_decision3'] = 1 ;
											break;
										}
						if($_SESSION['creation_decision3']!=0){
							break;
						}
					}
			}
			
	}
			
			if($_SESSION['creation_decision3'] == 0){	
				
				foreach($_SESSION["cart_item2"] as $item){
				$id2 = $item["id"];
				$stocks2 = $item["stock"];
				
				$update_query = "UPDATE tbl_products SET sold=sold+$stocks2 WHERE name='$id2'";
				$update = mysqli_query($connect, $update_query); 

				$update_query2 = "UPDATE tbl_products SET stock=stock-$stocks2 WHERE name='$id2'";
				$update2 = mysqli_query($connect, $update_query2); 				
				
				}
				echo "<script>alert('$id2 success!');</script>";
				
				
				header("location:form-step.php");
				
				}
				
	}
	
	

?>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include 'header.php'; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
		  <?php include 'header_order.php'; ?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->
		  <?php include 'sidebar_order.php'; ?>
        
      
        


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">



<!--

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Online Shopping</a></li>
                    </ol>
                </div>
            </div>
			
			-->
            <!-- row -->
			
			

		<div class="container-fluid">
                <!-- End Row -->
                <div class="row">
                    <div class="col-12 m-b-30">
                        <div class="row">
						 
						 <?php
						  $connect = mysqli_connect("localhost", "root", "", "shoppingcart_db");
							if(mysqli_connect_errno()){
							echo "CONNECTION FAILED! ". mysqli_connect_errno();
							}
								$search_query = "SELECT * FROM tbl_products WHERE status='1'";
								$search = mysqli_query($connect, $search_query);

								if(mysqli_num_rows($search)>0)
								{
									while($row=mysqli_fetch_array($search))
									{	
									?>				
										<div class="col-md-3 col-lg-3">
											<div class="card">
												<input class="img-fluid" type="image" img src ="<?php echo $row['image']?>" data-toggle="modal" data-target="#<?php echo $row['code']?>"></input>
												<div class="modal fade" id="<?php echo $row['code']?>">
													<div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title"><?php echo $row['name']?></h5>
																<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
																</button>
															</div>
															<div class="modal-body">
															<p><center><input width="300px" height="200px" type="image" img src ="<?php echo $row['image']?>"></center></input></p>
															<p><center><i><?php echo $row['description']?></i></center></p>
															<p><center><font size="2px"><i><?php echo $row['stock'];?> stocks available</i></font></p>
															</div>
															<div class="modal-footer">
																<form method="post" action="cart_function2.php">	
																	<?php if($row['stock'] == 0){?>
																	<center><i><font size="3px">Out of Stock</font></i></center>
																	<?php }else{ ?>
																	<input type="number" style="width: 8em" id="ip2" min="1" value="1"  name = "stock" >
																	<button class="btn gradient-9 btn-lg border-0 btn-rounded px-3" name= "add" type="submit">
																	<font size="3px">Add to Bag </font>
																	</button>
																	<input type="hidden" value="<?php echo $row['name']?>" name = "name">
																	<input type="hidden" value="<?php echo $row['price']?>" name = "price">	
																	<?php } ?>																	
																</form>
															</div>
														</div>
													</div>
												</div>
												
												<div class="card-body">
													<h5 class="card-title"><?php echo $row['name']; ?></h5>
													<p class="card-text">
													<font size="4px"> ₱ <?php echo $row['price'];?>  </font>
													<?php if($row['stock'] == 0){?>
														
															<center><i><font size="3px">Out of Stock</font></i>
															<?php }else{ ?>
															<br>
															<font size="2px"><i><?php echo $row['stock'];?> stocks available</i></font>
															
														<form method="post">	
															<input type="number" style="width: 8em" id="ip2" min="1" value="1"  name = "stock" >
															<button class="btn gradient-9 btn-lg border-0 btn-rounded px-4" name= "add" type="submit" id="toastr-success-bottom-right">
															<i class="fa fa-cart-plus" size="5px" ></i>		
															</button>
															<input type="hidden" value="<?php echo $row['name']?>" name = "name">
															<input type="hidden" value="<?php echo $row['price']?>" name = "price">
															<?php } ?>
														</form>												
													</p>
												</div>
											</div>
										</div>
										<!-- End Col -->
										<?php
										}
									}
									?>	
									</div>
										</div>
								</div>
							</div>
              
									
		
		
		

                  

        <!--**********************************
            Content body end
        ***********************************-->
        
        
        <!--**********************************
            Footer start
      
        <div class="footer">
            <div class="copyright">
                <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a> 2018</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
   
	<script src="./plugins/toastr/js/toastr.min.js"></script>
    <script src="./plugins/toastr/js/toastr.init.js"></script>

   
</body>

</html>
