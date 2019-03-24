<?php
function searchInput($searchValue, $database, $databaseColumn){
	$searchValue = htmlspecialchars($searchValue);
	//pull any data which contains the suburb entered
	$results = $database->prepare("SELECT * FROM items WHERE $databaseColumn LIKE :searchValue");
	$results->bindValue(':searchValue', '%'.$searchValue.'%');
	$results->execute();
	$i = 1;
	//convert the results to an array
	$search_results = array();
	foreach($results as $item){
		$a = array('hotspotId'=>$item['hotspotId'], 'hotspotName'=>$item['hotspotName'], 'address'=>$item['address'], 'suburb'=>$item['suburb'], 'latitude'=>$item['latitude'], 'longitude'=>$item['longitude']);
		$search_results[$i] = $a;
		$i = $i + 1;
	}
	//add the search results to a session variable
	$_SESSION['hotspots'] = $search_results;
	//if there are no results, set the session variable 'noResults' to true, otherwise set it to false.
	if (empty($search_results)){
		$_SESSION['noResults'] = true;
	} else{
		$_SESSION['noResults'] = false;
	}
	//set the value searched session variable to the inputted suburb
	$_SESSION['valueSearched'] = $searchValue;
	//set the searched session variable to true
	$_SESSION['searched'] = true;
}
//check if the input search button is selected
if (isset($_GET['input-search'])){
	//pull the inputs
	$suburb = $_GET['suburbs'];
	$name = $_GET['name'];
	$rating = $_GET['ratings'];
	//check if the suburb input is not null
	if ($suburb!=null && $suburb!='suburb'){
		try{
			searchInput($suburb, $database, 'suburb');

			$_SESSION['searchedSuburb'] = true;
			$_SESSION['searchedName'] = false;
			//send the user to the search results page
			header("location: search-results.php");
			exit();
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	} else if ($name!=null){
		try{
			searchInput($name, $database, 'hotspotName');
			$_SESSION['searchedSuburb'] = false;
			$_SESSION['searchedName'] = true;
			//send the user to the search results page
			header("location: search-results.php");
			exit();
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	} else if ($rating!=null && $rating!='rating'){
		try{
			//pull any data which contains the rating entered
			$intRating = (int)$rating;
			$results = $database->prepare("SELECT * FROM items, reviewsRating WHERE items.hotspotId = reviewsRating.hotspotId AND reviewsRating.averageRating = :intRating");
			$results->bindValue(':intRating', $intRating);
			$results->execute();
			//convert the results to an array
			$i = 1;
			$search_results = array();
			foreach($results as $item){
				$a = array('hotspotId'=>$item['hotspotId'], 'hotspotName'=>$item['hotspotName'], 'address'=>$item['address'], 'suburb'=>$item['suburb'], 'latitude'=>$item['latitude'], 'longitude'=>$item['longitude']);
				$search_results[$i] = $a;
				$i = $i + 1;
			}
			//add the search results to a session variable
			$_SESSION['hotspots'] = $search_results;
			//if there are no results, set the session variable 'noResults' to true, otherwise set it to false.
			if (empty($search_results)){
				$_SESSION['noResults'] = true;
			} else{
				$_SESSION['noResults'] = false;
			}
			//set the value searched session variable to the inputted rating
			$_SESSION['valueSearched'] = 'Rating ' . $rating;
			//set the searched session variable to true
			$_SESSION['searched'] = true;
			$_SESSION['searchedSuburb'] = false;
			$_SESSION['searchedName'] = false;
			//send the user to the search results page
			header("location: search-results.php");
			exit();
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	} else{
		//if there is no data inputted into the search form set the session variable for no-input as true
		$_SESSION['no-input']=true;
	}
//otherwise check if location searched button has been clicked
} else if (isset($_GET['location-search'])){
	//set value searched as my location
	$_SESSION['valueSearched'] = "My Location";
	$_SESSION['searched'] = true;

	//send the user to the search results page
	header("location: search-results.php");
}
?>