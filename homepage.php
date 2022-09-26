<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Gallery Database</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" />    
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
								<li class="tm-nav-li"><a href="homepage.php" class="tm-nav-link active">Home</a></li>
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
				<h2 class="col-12 text-center tm-section-title">Welcome to Gallery Database</h2>
				<p class="col-12 text-center">這座美術館內附有許多藝術品，您可以在這裡了解畫家、畫作的詳細資訊，這些藝術品來自四面八方的國家，每一幅都由我們細心挑選，留存在藝術館內，值得您細細品味，Gallery DataBase，為您帶來世界上千垂不朽的傳奇作品。</p>
			</header>
		</main>
		<br></br>
			

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