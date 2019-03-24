<?php
//list the stars out depending on the rating
switch ($rating){
    case 0:
    //if rating is 0, that means there is no reviews and hence display greyed out stars and a message saying no reviews.
        echo "No reviews<br />";
        echo '<span class="stars no-reviews coloured">&#9734;</span><span class="stars no-reviews coloured">&#9734;</span><span class="stars no-reviews coloured">&#9734;</span><span class="stars no-reviews coloured">&#9734;</span><span class="stars no-reviews coloured">&#9734;</span>';
        break;
    case 1:
    // if the rating is 1, then we assign the first star the class "coloured", resulting in that star appeared as filled in
        echo '<span class="stars coloured">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span>';
        break;
    case 2:
    // if the rating is 2, then we assign the first two star the class "coloured", resulting in those star appeared as filled in
        echo '<span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span>';
        break;
    case 3:
    // if the rating is 3, then we assign the first three star the class "coloured", resulting in those star appeared as filled in    
        echo '<span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span>';
        break;
    case 4:
    // if the rating is 4, then we assign the first four star the class "coloured", resulting in those star appeared as filled in
        echo '<span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars">&#9734;</span>';
        break;
    case 5:
    // if the rating is 5, then we assign all stars the class "coloured", resulting in all of the stars being filled in
        echo '<span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span><span class="stars coloured">&#9734;</span>';
}
?>