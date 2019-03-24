<?php
//check if the submit button has been pressed
if(isset($_POST['add-review'])){
	//check if the rating is empty or on the default value of 'rating' (placeholder) and if the review is empty
	if($_POST['rating']!=null && $_POST['rating']!='rating' && $_POST['review']!=null){
		//pull inputted review value
		$review = $_POST['review'];
		//convert all html characters to the equivalent html symbol entities
		$review = htmlspecialchars($review);
		//pull inputted rating value
		$rating = $_POST['rating'];
		//pull members ID
		$memberId = $_SESSION['memberId'];
		//pull today's date
		$date = date("Y-m-d");
		//insert new review into the reviews table in the database using a prepare sql query followed by binding values to prevent SQL injection attacks
		$statement = $database->prepare("INSERT INTO reviews(memberId, hotspotId, rating, reviewDate, review)
										VALUES(:memberId, :hotspotId, :rating, :reviewDate, :review)");
		$statement->bindValue(':memberId', $memberId);
		$statement->bindValue(':hotspotId', $item);
		$statement->bindValue(':rating', $rating);
		$statement->bindValue('reviewDate', $date);
		$statement->bindValue('review', $review);
		$statement->execute();

		//pull all ratings for the current hotspot
		$statement2 = $database->prepare("SELECT rating FROM reviews WHERE hotspotId = :hotspotId");
		$statement2->bindValue(':hotspotId', $item);
		$statement2->execute();
		//initialise sum of ratings and number of ratings
		$totalrating = 0.000000;
		$numRatings = 0;
		//calculate the sum of all ratings and the number of ratings
		foreach ($statement2 as $rating){
			$totalrating = $totalrating + $rating['rating'];
			$numRatings = $numRatings + 1;
		}
		//calculate the average rating
		$averageRating = $totalrating / $numRatings;
		//round the rating to an integer value for the star rating
		$intRating = round($averageRating);

		//update the reviewsRating table in the database with the new average int rating and the new number of reviews
		$statement3 = $database->prepare("UPDATE reviewsRating SET averageRating = :intRating, numReviews = :numReviews WHERE hotspotId = :hotspotId");
		$statement3->bindValue(':intRating', $intRating);
		$statement3->bindValue(':numReviews', $numRatings);
		$statement3->bindValue(':hotspotId', $item);
		$statement3->execute();

		//set the session variable results viewed as true and refresh the page to display the new review
		$_SESSION['results-viewed'] = true;
		header("location: item-results.php?hotspotId=$item");
	} else{
		//if no rating was entered set the rating null session variable to true - this will then display an error message when the item results page is refreshed
		if($_POST['rating']==null || $_POST['rating']=='rating'){
			$_SESSION['rating_null'] = true;
		}
		//if no review was entered set the review null session variable to true - this will then display an error message when the item results page is refreshed
		if ($_POST['review']==null){
			$_SESSION['review_null'] = true;
		}
	}
}
?>