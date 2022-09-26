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
			<div class="parallax-window" data-parallax="scroll" data-image-src="img/The Starry Night.jpg">
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
				<?php
					$key = $_POST['keyword'];
					echo "<p class='col-12 text-center'>"."有關「".$key."」的所有搜尋結果"."</p>";
				?>
			</header>

			<div class="tm-container-inner tm-persons">
				<div class="row">
					<?php
						$link = mysqli_connect('140.127.220.233','a1083305','a1083305Checkpoint7','a1083305');
						$key = $_POST['keyword'];
						/* Artist */ 
						$SQL = "SELECT * FROM Artist WHERE aname like '%$key%' OR birthplace like '%$key%' OR age like '%$key%' OR aname like '%$key%'";
						$result = mysqli_query($link,$SQL);
						while($row = mysqli_fetch_assoc($result)){
							$picture = $row["aname"].".jpg";
							echo "<article class='col-lg-6'>";
								echo "<figure class='tm-person'>";
									echo "<img src='img/artist/$picture' alt='Image' class='img-fluid tm-person-img' />";
									echo "<figcaption class='tm-person-description'>";
										$id = $row['aname'];
										echo "<h4 class='tm-person-name'><a href='artist_paint.php?id=$id '>"."<b>".$row['aname']."</b>"."</a></h4>";
										//echo "<p class='tm-person-title'>"."Founder and CEO"."</p>";
										echo "<p class='tm-person-about'>"."Birthplace: ".$row['birthplace']."<br>"."Aged: ".$row['age']."<br>"."Style: ".$row['style']."</p>";
									echo "</figcaption>";
								echo "</figure>";
							echo "</article>";
						}
						/* Artist */

						/* Customer*/
						$SQLc = "SELECT * FROM Customer";
						$SQLc2 = "SELECT * FROM Customer_Like WHERE aname like '%$key%' OR cname like '%$key%' OR gname like '%$key%'";
						$resultc = mysqli_query($link,$SQLc);
						$resultc2 = mysqli_query($link,$SQLc2);
						while($rowc = mysqli_fetch_assoc($resultc)){
							$resultc2 = mysqli_query($link,$SQLc2);
							while($rowc2 = mysqli_fetch_assoc($resultc2)){
								if($rowc["cname"] == $rowc2["cname"]){
									echo "<article class='col-lg-6'>";
										echo "<figure class='tm-person'>";
											echo "<figcaption class='tm-person-description'>";
												echo "<h4 class='tm-person-name'>"."<b>".$rowc["cname"]."</b>"."</h4>";
												echo "<p class='tm-person-title'>"."Total Amount: "."<b>".$rowc["amount"]."</b>"." U.S.D(Million)"."</p>";
												echo "<p class='tm-person-about'>"."地址: ".$rowc["address"]."<br>"."喜歡的藝術家: ".$rowc2["aname"]."<br>"."喜歡的藝術類型: ".$rowc2["gname"]."</p>";
											echo "</figcaption>";
										echo "</figure>";
									echo "</article>";
								}
							}
						}
						/* Customer */

						/* Artwork */
						$SQLwork = "SELECT * FROM Artwork WHERE title like '%$key%' OR aname like '%$key%' OR type like '%$key%' OR year like '%$key%' OR price like '%$key%' OR gname like '%$key%'";
						$resultwork = mysqli_query($link,$SQLwork);
						while($rowwork = mysqli_fetch_assoc($resultwork)){
							$picture = $rowwork["title"].".jpg";
							echo "<article class='col-lg-3 col-md-4 col-sm-6 col-12 tm-gallery-item'>";
								echo "<figure>";
									echo "<img src='img/gallery/$picture' alt='Image' class='img-fluid tm-gallery-img' />";
									echo "<figcaption>";
										echo "<h4 class='tm-gallery-title'>".$rowwork["title"]."<br>"."<span style='font-size:13px;'>"."by ".$rowwork["aname"]."</span>"."</h4>";
										echo "<p class='tm-gallery-description'>"."# ".$rowwork["gname"]."</p>";
										echo "<p class='tm-gallery-price'>"."$"."<b>".$rowwork["price"]."</b>"." U.S.D(Million)"."</p>";
									echo "</figcaption>";
								echo "</figure>";
							echo "</article>";
							/*
							$i=$rowwork["aname"];
							$SQLauthor = "SELECT * FROM Artist WHERE aname = '$i'";
							$resultauthor = mysqli_query($link,$SQLauthor);
							while($rowauthor = mysqli_fetch_assoc($resultauthor)){
									$picture = $rowauthor["aname"].".jpg";
									echo "<article class='col-lg-6'>";
									echo "<figure class='tm-person'>";
										echo "<img src='img/artist/$picture' alt='Image' class='img-fluid tm-person-img' />";
										echo "<figcaption class='tm-person-description'>";
										$id = $rowauthor['aname'];
											echo "<h4 class='tm-person-name'><a href='artist_paint.php?id=$id '>"."<b>".$rowauthor['aname']."</b>"."</a></h4>";
										//echo "<p class='tm-person-title'>"."Founder and CEO"."</p>";
											echo "<p class='tm-person-about'>"."Birthplace: ".$rowauthor['birthplace']."<br>"."Aged: ".$rowauthor['age']."<br>"."Style: ".$rowauthor['style']."</p>";
										echo "</figcaption>";
									echo "</figure>";
								echo "</article>";
							}*/
						}
						/* Artwork */
					?>
				</div>
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

