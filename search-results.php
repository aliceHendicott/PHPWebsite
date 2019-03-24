<!-- check if user has gotten to this page through logical progression - by searching. Otherwise force user back to search page-->
<?php
	require "includes/session.php";
	if(!isset($_SESSION['searched'])){
		header("location: search-page.php");
	} else{
		unset($_SESSION['searched']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- link to CSS stylesheets-->
		<?php
			require "includes/CSS_link.php";
		?>
		<!--add title of search results page-->
		<title>hotspot.com - Search Results</title>
		<meta charset="utf8" />
		<meta name="author" content="Cameron Murray, Bradley Park & Alice Hendicott"/>
		<meta name="description" content="Hotspot Search Result" />
		<meta name="keywords" content="Hotspot, Search Result" />
		<meta name="robots" content="noindex" />
		</head>
		<body>
		
		
        <?php
        	//set up header
			require_once("includes/header.php");
			//pull locations for hotspots
			require_once("includes/grab-locs.php");
        ?>
			
		<div id="sub-header">
				<h2 class="page-header">Search Results</h2>
		</div>
		<!-- CONTENT section-->
		<div id="content">
			<div id="results-showing"><?php
						//pull the value that was searched and display it on the screen
						$search = $_SESSION['valueSearched'];
						if (isset($_SESSION['noResults']) && $_SESSION['noResults'] == true){
							//if there are no results display a message informing this
							echo "There are no search results for your search '$search'";
						} else{
							echo "Showing search results for '$search'";
						}
				?></div>
			<!-- add map to the page-->
			<div id="map" class="map"></div>
			<div class="search-item-container">
				<?php
					//list the results in grid format
					require "includes/tiles.php";
				?>
			</div>
		</div>
		<div id="spacer"></div>
		<?php
			//add footer to the page
            include("includes/footer.php");
		?>
		<!-- link to javascript files-->
		<script language="javascript" type="text/javascript" src="JavaScript/search_results.js"></script>
		<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuxu0DS-iYivQ4yXiCVyDa7F7ZegaLfOY" type="text/javascript"></script>
	</body>
</html>