<!-- check if the user has gotten to this page through logical progression - this will only happen when the URL has the ID listed as a GET query -->
<?php
    require "includes/session.php";
    if(!isset($_GET['hotspotId'])){
        header("location: search-page.php");
    }
?>
<!DOCTYPE html>
<html>
	<head>
        <!-- link to the css stylsheet and pull the details of the hotspot based on the ID -->
        <?php
			require "includes/CSS_link.php";
	        require "includes/grab-values.php";
        ?>
        <!-- set the title of the page as the name of the hotspot-->
        <title><?php echo $name; ?></title>
		<meta charset="utf8" />
		<meta name="author" content="Cameron Murray, Bradley Park & Alice Hendicott"/>
		<meta name="description" content="Hotspot Search Result item" />
		<meta name="keywords" content="Hotspot, Search Result time" />
		<meta name="robots" content="noindex" />
	</head>
	<body>
        <?php
            /*set the header up*/
            include "includes/header.php";
            include "includes/add-review.php";	
        ?>
	
	<!-- set up header on the website-->
        <div id="sub-header">
             <h2 class="page-header"><?php echo "$name"; ?></h2>
        </div>
        <!-- place the map on the screen, the javascript file deals with adding the pins to the map-->
        <div id="results-map" class="map"></div>
        <!-- This section lists the deatils related to the hotspot in grid format-->
        <div id="review-section">
            <h2 class="review-title" id="top-review-title">Details</h2></div>
            <div class="search-item-container">
                <?php
                /*pull the rating for the hotspot*/
                $statement = $database->prepare("SELECT averageRating, numReviews FROM reviewsRating WHERE hotspotId = :hotspotId");
                $statement->bindValue(':hotspotId', $item);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $rating = $result['averageRating'];
				$numrating = $result['numReviews'];
                /*display details on the screen*/
                echo "<div class=\"review\"><b>Address</b>: $address<br /></div>";
                echo "<div class=\"review\"><b>Suburb</b>: $suburb<br /></div>";
                echo "<div class=\"review\"><b>Average Rating</b>:<br />";
                /*this php include file deals with adding the star rating to the page*/
                include "includes/add-star-rating.php";
                echo "</div>";
                ?>
            </div>
        </div>
		
		<!-- Setting up the loaction and average rating meta data for the respective hotspot. -->	
		<div class="meta_data" itemscope itemtype="http://schema.org/Place">
			<!-- Settiing the name of the place to the name of the hotspot -->
			<p itemprop="name"> <?php echo $name;?> </p>
			<!-- Setting the Address of the particular hotspot -->
			<div itemscope itemprop="address" itemtype="http://schema.org/PostalAddress">
			<p itemprop="addressLocality"> <?php echo $suburb;?></p>
			<p itemprop="streetAddress"> <?php echo $address;?></p>
			<p itemprop="addressRegion"> QLD </p>
			</div>
			<!-- Setting the average ratting of the particular hotspot -->
			<div itemprop="aggregateRating"itemscope itemtype="http://schema.org/AggregateRating">
			<p itemprop="ratingValue"> <?php echo $rating ?> </p>
			<p itemprop="reviewCount"> <?php echo $numrating ?> </p>
			</div>
			<!-- Setting the longittude and latitude of the particular hotspot -->
			<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
			<p itemprop="latitude"><?php echo $lat;?> </p>
			<p itemprop="longitude"> <?php echo $long;?></p>
			</div>
		</div>
		
        <!--Add the reviews to the screen in grid format-->
        <div id="review-section"><h2 class="review-title" id="top-review-title">Reviews</h2></div>
        <div class="search-item-container">
            <!-- link to php file which handles listing the reviews-->
            <?php include "includes/list-reviews.php"; ?>
        </div>
        <!--Add form which allows logged in users to add reviews-->
        <div id="write-review">
            <h2 class="review-title" id="leave-review">Leave a Review:</h2>
			<!-- links to include file which handles adding the review form-->
            <?php include_once "includes/add-review-form.php";?>
		
        </div>
        <div id="container"></div>
        <!-- add footer to screen-->
        <?php
            include("includes/footer.php");
        ?>
        <!-- link to the javascript file for this page as well as the google API-->
        <script language="javascript" type="text/javascript" src="JavaScript/item_results.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuxu0DS-iYivQ4yXiCVyDa7F7ZegaLfOY&callback=initMap" type="text/javascript"></script>
	</body>
</html>