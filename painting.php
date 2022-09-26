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
			<div class="parallax-window" data-parallax="scroll" data-image-src="img/The_Creation_Of_Adam.jpg">
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
								<li class="tm-nav-li"><a href="painting.php" class="tm-nav-link active">Painting</a></li>
								<li class="tm-nav-li"><a href="customer.php" class="tm-nav-link">Customer</a></li>
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
				<h2 class="col-12 text-center tm-section-title">About The Paintings</h2>
				<p class="col-12 text-center">This is about page of paintings that are on exhibition. <br>
                You can buy them whatever you like !</p>
			</header>

			<!-- Gallery -->
			<div class="row tm-gallery">
				<!-- gallery page 1 -->
				<div id="tm-gallery-page-pizza" class="tm-gallery-page">

				<?php
				    $link = mysqli_connect('140.127.220.233','a1083305','a1083305Checkpoint7','a1083305');
    				$SQL = "SELECT * FROM Artwork";
    				$result = mysqli_query($link,$SQL);
    				while($row = mysqli_fetch_assoc($result)){
    					$picture = $row["title"].".jpg";
						echo "<article class='col-lg-3 col-md-4 col-sm-6 col-12 tm-gallery-item'>";
							echo "<figure>";
								echo "<img src='img/gallery/$picture' alt='Image' class='img-fluid tm-gallery-img' />";
								echo "<figcaption>";
									echo "<h4 class='tm-gallery-title'>".$row["title"]."<br>"."<span style='font-size:13px;'>"."by ".$row["aname"]."</span>"."</h4>";

									echo "<p class='tm-gallery-description'>"."# ".$row['gname']."</p>";
									echo "<p class='tm-gallery-price'>"."$"."<b>".$row["price"]."</b>"." U.S.D(Million)"."</p>";
								echo "</figcaption>";
							echo "</figure>";
						echo "</article>";
					}
				?>
				</div> <!-- gallery page 1 -->
			</div>
			
		</main>

	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/parallax.min.js"></script>
	<script>
		$(document).ready(function(){
			// Handle click on paging links
			$('.tm-paging-link').click(function(e){
				e.preventDefault();
				
				var page = $(this).text().toLowerCase();
				$('.tm-gallery-page').addClass('hidden');
				$('#tm-gallery-page-' + page).removeClass('hidden');
				$('.tm-paging-link').removeClass('active');
				$(this).addClass("active");
			});
		});
	</script>
</body>
</html>