<?php
    //count the number of results
    if ($_SESSION['valueSearched'] == "My Location") {
        $counter = count($hotspots);
        $results = [];
    }
    else {
        $results = $_SESSION['hotspots'];
        $counter = count($results);
    }
    $rating = [];
    $ratingArray = [];
    //for each hotspot result...
    for ($i = 1; $i <= $counter; $i++) {
        //pull data of each hotspotID
        if ($_SESSION['valueSearched'] == "My Location") {
            $hotspotId = "";
            $hotspotName = "";
            $suburb = "";
            $address = "";
            $ratingResults = $database->prepare('SELECT averageRating FROM reviewsRating WHERE hotspotId = :hotspotId');
            $ratingResults->bindValue(':hotspotId', $i);
            $ratingResults->execute();
            foreach($ratingResults as $hotspot){
                // Add the rating data to the array for use later when converting to JavaScript
                $ratingArray[$i] = ceil($hotspot['averageRating']);
            }
        }
        else {
            $hotspotId = $results[$i]['hotspotId'];
            $hotspotName = $results[$i]['hotspotName'];
            $suburb = $results[$i]['suburb'];
            $address = $results[$i]['address'];
            $latitude = $results[$i]['latitude'];
            $longitude = $results[$i]['longitude'];
            $ratingResults = $database->prepare('SELECT averageRating FROM reviewsRating WHERE hotspotId = :hotspotId');
            $ratingResults->bindValue(':hotspotId', $hotspotId);
            $ratingResults->execute();
            $ratingArray = $ratingResults->fetch(PDO::FETCH_ASSOC);
            $rating = $ratingArray['averageRating'];
        }
        //display data onto the screen in grid format
        echo '<div class="item">';
        echo '<div>';
        echo "<b class='hotspotNames'>$hotspotName</b>";
        echo "<p class='addresses'>$address</p>";
        echo "<p><a class='references' href=\"item-results.php?hotspotId=$hotspotId\">Read more</a></p>";
        if ($_SESSION['valueSearched'] == "My Location") {
            echo '<span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span><span class="stars">&#9734;</span>';
        }
        else {
            include "includes/add-star-rating.php";
        }
        echo '</div>';
        echo '<div class="item-div">';
        echo "<p class=\"map-id\"></p>";
        echo '<p class="distanceObject"></p>';
        echo '</div>';
        echo '</div>';
    }
    
    // Set up some variables that will be needed in search_results.js
    if (isset($_SESSION['searchedSuburb']) && $_SESSION['searchedSuburb']) {
        $searchedSuburbs = "true";
    }
    else {
        $searchedSuburbs = "false";
    }

    if (isset($_SESSION['searchedName']) && $_SESSION['searchedName']) {
        $searchedName = "true";
    }
    else {
        $searchedName = "false";
    }
?>

<!-- Convert all of the necessary PHP variables to JavaScript -->
<script language="javascript" type="text/javascript">
    var valueSearched = "<?php echo $_SESSION['valueSearched']; ?>";
    var searchedSuburbs = <?php echo $searchedSuburbs; ?>;
    var searchedName = <?php echo $searchedName; ?>;
    if (valueSearched == "My Location") {
        var ratings = <?php echo json_encode($ratingArray); ?>;
        var numResults = <?php echo count($ratingArray); ?>;
    }
    else {
        var results = <?php echo json_encode($results); ?>;
        var numResults = <?php echo count($results); ?>;
    }
</script>