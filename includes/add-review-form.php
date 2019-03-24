<?php
//if the user is not logged in, display a message informing them they can't leave reviews without being loggen in
//otherwise add the review form
if (!isset($_SESSION['loggedIn'])){
    echo "Please <a href='log-in-page.php'>log in</a> to leave a review<br />";
    echo "&nbsp;";
} else{
    echo "<form action=\"item-results.php?hotspotId=$item\" method=\"post\">";
    //display error message if the rating was not entered
    if (isset($_SESSION['rating_null'])){
        unset($_SESSION['rating_null']);
        echo '<label class="not-entered">Please enter a rating</label><br />';
    }
    //set up select input for the rating
    echo '<select name="rating" class="review-rating-input">';
    echo '<option value="rating">Rating</option>';
    echo '<option value="1">1</option>';
    echo '<option value="2">2</option>';
    echo '<option value="3">3</option>';
    echo '<option value="4">4</option>';
    echo '<option value="5">5</option>';
    echo '</select><br />';
    //display error message if the review was not entered
    if (isset($_SESSION['review_null'])){
        unset($_SESSION['review_null']);
        echo '<label class="not-entered">Please enter a review</label><br />';
    }
    //set up textarea input for the review
    echo '<textarea id="review-box" name="review" cols=50 rows=5 placeholder="Please write your review here..."></textarea><br />';

    //set up submit button for add review form
    echo '<input class="review-button" type="submit" value="Add Review" name="add-review"></input>';
    echo '</form>';
}

?>