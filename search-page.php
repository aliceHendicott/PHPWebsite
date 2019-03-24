<!-- start session -->
<?php
	require "includes/session.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Linking in the style sheets -->
		<?php
			require "includes/CSS_link.php";
		?>
		
		<!-- set title for this page-->
		<title>hotspot.com - Search</title>
		<meta charset="utf8" />
		<meta name="author" content="Cameron Murray, Bradley Park & Alice Hendicott"/>
		<meta name="description" content="Hotspot Search Page" />
		<meta name="keywords" content="Hotspot, Search Page" />
	</head>
	<body>
        <?php
        	//set up header
            include "includes/header.php";
            //include file which will handle searching based on the input of the search input
            include 'includes/search.php';
        ?>
        <!-- add banner-->
		<div id="banner">
			<div id="banner-container">
				<!-- add search form-->
				<div id="search">
					<h1>Find a Hotspot</h1>
					<div>
						<?php 
						//if no input was added show error asking for an input
						if(isset($_SESSION['no-input'])){
							unset($_SESSION['no-input']);
							echo '<label class="not-entered">Please enter an input in one of the three fields below or select search by location</label>';
						}
						?>
						 <form name="searchothers" action="search-page.php" method="get">
						 	<!-- Setting up suburb selction -->
							<select name="suburbs" class="search-item">
								<option value="suburb" default selected>Suburb</option>
								<?php
								try {
									//pull all distinct suburbs from the items database table
									$result = $database->query('SELECT DISTINCT suburb FROM items');
									//add each result from the query above to as a select option 
									foreach ($result as $suburb){
										echo '<option value="',$suburb['suburb'],'">',$suburb['suburb'],'</option>';
									}
								} catch (PDOException $e) {
									echo $e->getMessage();
								}
								?>
							</select>
							<p>OR</p>
							<!-- Setting up name input -->
							<input class="search-item text-item" type="text" placeholder="Name" name="name"></input>
							<p>OR</p>
							<!-- Setting up rating selection -->
							<select name="ratings" class="search-item">
								<option value="rating" defaul selected>Rating</option>
		  						<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
							<!-- Search button that usising input details nfi-->
							<input class="search-button" type="submit" value="Search hotspots" name="input-search"></input>
						</form>
						<hr />
						<!-- Search button that will use you locational data nfi-->
						<form name="searchlocs" action="search-page.php" method="get">
							<input class="search-button" type="submit" value="Search using your location" name="location-search"></input>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- set up footer-->
		<?php
            include("includes/footer.php");
        ?>
		<!-- Linking the corosponding java script file -->
		<script language="javascript" type="text/javascript" src="JavaScript/search_script.js"></script>
	</body>
</html>