<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Gallery Database</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" />
    <link href="css/all.min.css" rel="stylesheet" />
	<link href="css/templatemo-style.css" rel="stylesheet" />
</head>
<!--

Simple House

https://templatemo.com/tm-539-simple-house

-->
<body> 

	<div class="container">
	<!-- Top box -->
		<!-- Logo & Site Name -->
		<div class="placeholder">
			<div class="parallax-window" data-parallax="scroll" data-image-src="img/Marry.jpg">
				<div class="tm-header">
					<div class="row tm-header-inner">
						<div class="col-md-6 col-12">
							<img src="img/simple-house-logo.png" alt="Logo" class="tm-site-logo" /> 
							<div class="tm-site-text-box">
								<h1 class="tm-site-title">Gallery</h1>
								<h6 class="tm-site-description">with top paintings here</h6>	
							</div>
						</div>
						<nav class="col-md-6 col-12 tm-nav">
							<ul class="tm-nav-ul">
								<li class="tm-nav-li"><a href="homepage.php" class="tm-nav-link">Home</a></li>
								<li class="tm-nav-li"><a href="artist.php" class="tm-nav-link">Artist</a></li>
								<li class="tm-nav-li"><a href="painting.php" class="tm-nav-link">Painting</a></li>
								<li class="tm-nav-li"><a href="customer.php" class="tm-nav-link active">Customer</a></li>
								<li>
									<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
									<form name="form1" method="post" action="search.php">
  										<input type="search" name="keyword">
  										<i class="fa fa-search"></i>
									</form>
								</li>
							</ul>
						</nav>	
					</div>
				</div>
			</div>
		</div>

		<main>
			<header class="row tm-welcome-section">
				<h2 class="col-12 text-center tm-section-title">About Our Customers</h2>
				<p class="col-12 text-center">This is about page of our customer. <br>
                You can see some basic information about them.</p>
			</header>

			<div class="tm-container-inner tm-persons">
				<div class="row">
					<?php
						$link = mysqli_connect('140.127.220.233','a1083305','a1083305Checkpoint7','a1083305');
						$SQL = "SELECT * FROM Customer";
						$SQL2 = "SELECT * FROM Customer_Like";
						$result = mysqli_query($link,$SQL);
						$result2 = mysqli_query($link,$SQL2);
						while($row = mysqli_fetch_assoc($result)){

							$result2 = mysqli_query($link,$SQL2);

							while($row2 = mysqli_fetch_assoc($result2)){
								if($row["cname"] == $row2["cname"]){
									echo "<article class='col-lg-6'>";
										echo "<figure class='tm-person'>";
											echo "<figcaption class='tm-person-description'>";
												echo "<h4 class='tm-person-name'>"."<b>".$row["cname"]."</b>"."</h4>";
												echo "<p class='tm-person-title'>"."Total Amount: "."<b>".$row["amount"]."</b>"." U.S.D(Million)"."</p>";
												echo "<p class='tm-person-about'>"."地址: ".$row["address"]."<br>"."喜歡的藝術家: ".$row2["aname"]."<br>"."喜歡的藝術類型: ".$row2["gname"]."</p>";
											echo "</figcaption>";
										echo "</figure>";
									echo "</article>";
								}
							}
						}

					?>
				</div>
			</div>
			<div class="tm-container-inner tm-featured-image">
				<div class="row">
					<div class="col-12">
						<div class="placeholder-2">
							<div class="parallax-window-2" data-parallax="scroll" data-image-src="img/Marry.jpg"></div>		
						</div>
					</div>
				</div>
			</div>
			
		</main>

		<footer class="tm-footer text-center">
			<p> </p>
		</footer>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/parallax.min.js"></script>
</body>
</html>