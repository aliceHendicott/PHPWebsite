<?php
try{
    //pull review info for current hotspot
    $reviewResults = $database->prepare('SELECT firstName, lastName, rating, reviewDate, review
                                        FROM members, reviews
                                        WHERE reviews.hotspotId = :hotspotId AND reviews.memberId = members.Id');
    $reviewResults->bindValue(':hotspotId', $item);
    $reviewResults->execute();
    //if there are no reviews list a message informing the user of this
    if ($reviewResults->rowCount()==0){
        echo "There are no reviews for this hotspot yet. Be the first to review $name hotspot and add a review below.";
    } else{
        //list each review in grid format with the name of the user who posted it, their rating, review and the date the review was posted
        $i = 1;
        foreach($reviewResults as $review){
            $fname = $review['firstName'];
            $lname = $review['lastName'];
            $rating = $review['rating'];
            $reviewDate = $review['reviewDate'];
            $review = $review['review'];
            echo '<div id="review'.$i.'" class="review">';
            echo "<b>$fname $lname</b><br />";
            echo "$reviewDate<br />";
            include "includes/add-star-rating.php";
            echo "<p>$review</p>";
            echo '</div>';
            $i = $i + 1;
			
			// Setting up the review and review rating meta data for every review for the respective hotspot. 
			echo '<div class="meta_data" itemprop="review" itemscope itemtype="http://schema.org/Review">';
			echo '<p itemprop="itemReviewed"> '. $name . '</p>';
			echo '<p itemprop="author"> ' . $fname . ' ' . $lname . '</p>';
			echo '<p itemprop="datePublished">' . $reviewDate . '</p>';
			echo '<p itemprop="description">' . $review . '</p>';
			echo '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
			echo '<p itemprop="ratingValue">' . $rating .'</p>';
			echo '</div>';
			echo '</div>';



			
			
			
			
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>