<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Inikoo &#124; Business - Portfolio</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />	
	<!-- Start CSS -->
		<link href="css/style.css" type="text/css" rel="stylesheet" media="all" />
		<link href="css/blue.css" type="text/css" rel="stylesheet" media="all" />
		<link href="css/anythingSlider.css" type="text/css" rel="stylesheet" media="all" />
		<!--[if IE 6]>
		   <link href="css/ie6.css" type="text/css" rel="stylesheet" media="all" />
		<![endif]-->	
		<!--[if IE 7]>
		   <link href="css/ie7.css" type="text/css" rel="stylesheet" media="all" />
		<![endif]-->
		<!--[if IE 9]>
		   <link href="css/ie9.css" type="text/css" rel="stylesheet" media="all" />
		<![endif]-->			
	<!-- End CSS -->
	<!-- Start Javascript -->
		<!--[if IE 6]>
			<script src="js/DD_belatedPNG.js"></script>
			<script>
			  DD_belatedPNG.fix('.ie6fix');   /* Add this class to any PNG that needs to have transparency fixed for IE 6 */
			</script>
		<![endif]--> 		
		<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
		<script src="js/jquery.easing.1.2.js" type="text/javascript"></script>	
		<script src="js/jquery.anythingslider.js" type="text/javascript"></script>
		<script type="text/javascript">
		{literal}
			$(document).ready(function(){
				 //Anything-Slider 			
					function formatText(index, panel) {
						  return index + "";
					  };
				
					$(function () {
						$('.anythingSlider').anythingSlider({
							easing: "easeInOutExpo",        // Anything other than "linear" or "swing" requires the easing plugin
							autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not.
							delay: 9000,                    // How long between slide transitions in AutoPlay mode
							startStopped: false,            // If autoPlay is on, this can force it to start stopped
							animationTime: 800,             // How long the slide transition takes
							hashTags: false,                 // Should links change the hashtag in the URL?
							buildNavigation: false,          // If true, builds and list of anchor links to link to each slide
								pauseOnHover: true             // If true, and autoPlay is enabled, the show will pause on hover
						});
					});			
			}); 	
		{/literal}
		</script>	
		<script src="js/superfish.js" type="text/javascript"></script>	
		<script src="js/jquery.tweet.js" type="text/javascript"></script>
		<script src="js/common.js" type="text/javascript"></script>
	<!-- End Javascript -->		
</head>
<body>
<!-- Start Header -->
<div id="header">
<a href="{$current_page}?lang=en_GB"><img  style="cursor:pointer;padding:0;margin-bottom:40px;margin-left:700px" src="images/tmb.lang.en_GB.png"  alt="GB"/></a>
<a href="{$current_page}?lang=es_ES"><img  style="cursor:pointer;padding:0;margin-bottom:40px;margin-left:10px" src="images/tmb.lang.en_GB.png"  alt="ES"/></a>
	<!-- Start Logo -->
	<h2 id="logo" class="round-bottom"><a href="index.php" class="ie6fix" >Inikoo</a></h2>
	<!-- End Logo -->	
	<!-- Start Navigation -->
	<div id="main-nav">
		<ul class="sf-menu">
			<li>
				<a id="nav-selected" class="nav-item" href="index.php">Home</a>
			</li>
			<li>
				<a class="nav-item" href="about.php">About</a>
			</li>
			<li class="dropdown">
				<a class="nav-item" href="features.php">Features</a>
				<ul class="dropdown-wrap">
					<li>
						<a href="feature-detail.php">Feature Detail</a>
					</li>
					<li>
						<a href="pricing.php">Plans &amp; Pricing</a>
					</li>					
					<li>
						<a href="index-alt.php">Homepage Option 2</a>
					</li>
					<li>
						<a href="index-alt2.php">Homepage Option 3</a>
					</li>											
					<li class="nav-flyout">
						<a href="#">Menu Item with Subnav</a>
						<ul>
							<li>
								<a href="#">Subnav-Link</a>
							</li>
							<li>
								<a href="#">Subnav-Link</a>
							</li>														
						</ul>
					</li>																						
				</ul>
			</li>	
			<li class="dropdown">
				<a class="nav-item" href="portfolio.php">Portfolio</a>
				<ul class="dropdown-wrap">
					<li>
						<a href="portfolio.php">1 Column Portfolio</a>
					</li>
					<li>
						<a href="portfolio-3column.php">3 Column Portfolio</a>
					</li>		
					<li>
						<a href="portfolio-detail.php">Portfolio Detail</a>
					</li>														
				</ul>
			</li>	
			<li class="dropdown">
				<a class="nav-item" href="blog.php">Blog</a>
				<ul class="dropdown-wrap">
					<li>
						<a href="blog.php">Blog Post List</a>
					</li>
					<li>
						<a href="blog-post.php">Post Page</a>
					</li>											
				</ul>
			</li>
			<li>
				<a class="nav-item" href="contact.php">Contact</a>
			</li>																		
		</ul>
	</div>
	<!-- End Navigation -->
</div>
<!-- End Header -->