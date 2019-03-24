// Sorry in advance to the person marking this, I know this won't be fun to follow...

// Get the current width and height of the window
w = window.innerWidth;
h = window.innerHeight;

// This function is set up to ensure that the footer on the page is always centered at the bottom of the page if there isn't enough content to push it down normally.
function setUpScreen (dif, difw) {
    // Determine the number of columns that the results are displaying in based on width of the screen
    if (difw > 970) {
        num_columns = 3;
    }
    else if (difw > 600) {
        num_columns = 2;
    }
    else {
        num_columns = 1;
    }
    // Determine the number of rows based on the number of elements being displayed and the number of columns there are being displayed in
    num_rows = Math.ceil(numResults / num_columns);

    // Height of each grid element
    grid_height = 146; //px

    // Height of all the main elements
    var headerHeight = 60;
    var footerHeight = 240;
    var minResultsHeight = grid_height * num_rows + 770 - 195;

    // Basically check that the total size of the content is smaller than the size of the window (including the height of the footer) and if so, then we know that there
    // is some left over space for the footer to go.
    if (dif > minResultsHeight + headerHeight + footerHeight) {
        var len = dif - headerHeight - footerHeight - minResultsHeight - 59;

        // Grab the content element (div that encases all of the content on the page - excluding the header and footer) and give it a set height so that the footer is forced to the bottom of the page
        document.getElementById("spacer").style.height = len + "px";
    }
}

// Run the above function on start to ensure the page looks right
setUpScreen(h, w);

// Set up a listener just in case they change the window size and redefine height and width variables and then call the above function to check everything still looks nice
addEventListener("resize", function(event) {
    w = window.innerWidth;
    h = window.innerHeight;

	setUpScreen(h, w);
	// smallScreen(w);
});


//********************************************//
//             End Setup of Screen            //
//********************************************//




// Begin the actual fun stuff

// Initialise possibly the most important array (will contain all of the data relating to every item in the items table)
var locs = [];

// Iterate through all of the data pulled from the database and store it accordingly in the locs variable for later use
for (i = 0; i < hotspots.length; i++) {
    var this_array = [parseFloat(lats[i]), parseFloat(longs[i]), hotspots[i], addresses[i], suburbs[i]];
    locs.push(this_array);
}


// This function gets the location of the user by requesting access to their current location.
function getLocation () {
    // Check that the browser supports geolocation
	if (navigator.geolocation) {
        // If the browser supports geolocation and the agrees to allow access to location then the function getPosition is called, and if they don't then showBris is called (more about these later)
		navigator.geolocation.getCurrentPosition(getPosition, showBris);
	} else {
        // If the browser doesn't support geolocation, basically tell them tough luck
		document.getElementById("status").innerHTML="Geolocation is not supported by this browser.";
	}
}


// This function determines the distance between two points
function distance (lat1, long1, lat2, long2) {
    // Radius of the Earth
    var R = 6371e3; // metres
    // Convert all of the coordinates to radians
	var lat1Rad = lat1 * Math.PI / 180;
	var lat2Rad = lat2 * Math.PI / 180;
	var lon1Rad = long1 * Math.PI / 180;
    var lon2Rad = long2 * Math.PI / 180;
    // Find difference in lat and long
	var dlat = (lat2Rad-lat1Rad);
	var dlon = (lon2Rad-lon1Rad);

    // Honestly thank you to whoever the guy was that came up with this formula, but basically my understanding is that it determines the curvature of the earth
	var a = Math.sin(dlat/2) * Math.sin(dlat/2) +
			Math.cos(lat1) * Math.cos(lat2) *
			Math.sin(dlon/2) * Math.sin(dlon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    
    // Multiply previous numbers together to get the distance between the two coordinates and return this value
	return d = R * c;
}


// As the name suggests, this function orders the array that is put into it.  This is used for when the user searches by location, as the results will be displayed based on
// the distance they are away from the item
function order_array (some_array) {
    // Get the number of times the for loops will run
    var run_time = some_array.length;
    // Initialise an empty array
    var array_2 = [];
    // Run through the loop and copy all of the data from the initial array to a dummy array.  It should be noted here that I also push the index of the item to the array for future use.
    // This is because when the array is reordered, the actual order will be very difficult to find, so by adding the index to their position in the "locs" array, I can use it to get all
    // of the other data associated with that particular distance later on (this index is passed to all subsequent arrays).
    for (var i = 0; i < run_time; i++) {
        var pushable_item = [some_array[i], i];
        array_2.push(pushable_item);
    }

    // Initialise another empty array
    var ordered_array = [];
    // Run through the dummy array (array_2) and determine which element has the shortest distance (initial distance is stupidly high so any distance will be smaller than the initial)
    // This for loop will run for the same number of times as there are elements in the array that was put into the function
    for (var i = 0; i < run_time; i++) {
        var smallest = 10000000000000;
        var smallest_index = 0;
        // This for loop will run through all of the remaining possible distances to find the smallest distance.
        for (var j = 0; j < array_2.length; j++) {
            // Assign new variable to compare against current "best" distance
            var comp = array_2[j][0];

            // If the current compare value is smaller than the current "smallest" value, then the value of "smallest" now becomes the current compare distance and the index of that distance is also recorded
            if (comp < smallest) {
                smallest = comp;
                smallest_index = j;
            }
        }
        // Now that we know the smallest distance from those that remain, we add it the ordered_array
        ordered_array.push(array_2[smallest_index]);
        // And then remove that value from the remaining options so that it isn't picked out again
        array_2.splice(smallest_index, 1);
    }

    // Return the ordered array
    return ordered_array;
}

// Initialise an array
exact_distances = [];

// As you can probably guess, this function gets the distance from the user to each of the items
function get_distance (lata, longa) {
    // Initialise yet another array (I need a lot of them)
    places_in_range = [];
    // Check that we're searching based on the user's location
    if (valueSearched == "My Location") {
        // If we are, then we use the "locs" array that we set up earlier
        for (var i = 0; i < locs.length; i++) {
            // Get the distance between the user and the first location item in the list of items
            var d = distance(lata, longa, locs[i][0], locs[i][1]);
            // Add this distance the the array "exact_diatances"
            exact_distances.push(d);
        }

        // Create new array called "new_distances" and set it equal to the output of the "order_array" funtion that you saw just before.
        var new_distances = order_array(exact_distances);

        // Set an initial search radius (would have made it changable for the user if I had a bit more time - map broke every time anything changed and took forever to fix)
        search_radius = 100; //km  //need to change to input when page has one

        // Iterate through the new for loop and check that the distances are within the search radius
        for (var i = 0; i < new_distances.length; i++) {
            // Those within the search radius are pushed to the array "places_in_range" which is used throughout the rest of the code
            if (new_distances[i][0] / 1000 <= search_radius) {
                places_in_range.push(new_distances[i]);
            }
        }
    }
    else {
        // If we get here then it's safe to say that we aren't searching by location and will need to use some different arrays with data since we only want some of the data, not
        // all of it.  This data is already provided in PHP and we have already converted it to JavaScript variables earlier, to be called now.
        // numResults is a variable that stores the number of different items stored in the data (easy for constructing for loops - you may notice I quite like for loops in this). 
        for (var i = 1; i <= numResults; i++) {
            // Calculate distance between the searched items and the user
            var d = distance(lata, longa, results[i]['latitude'], results[i]['longitude']);
            // Push the distances to the array "exact_distances".
            exact_distances.push(d);

            // This time we don't need to order since the results generally will be closer together, so just add them to the "places_in_range" array straight away.  Once again we push
            // the distance and the index of the item.
            to_push = [exact_distances[i - 1], results[i]['hotspotId'] - 1];
            places_in_range.push(to_push);
        }
    }
}

// Set up some variables for creating labels of any combination from A - ZZ (in other words a lot of combinations).
var labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var labelIndex = 0;
var labelIndex2 = 0;

// This is the main function to generate the interactive map.
function generateMap (lata, longa) {
    // Firstly we need to set the zoom level of the map (for use later)
    zoomLevel = 14;
    // Once again need to check what we're basing the search on since some parameters will be different.
    // If the user is searching by location the function uses the latitude and longitude coordinates provided to the function.
    if (valueSearched == "My Location") {
        var myLatLng = {lat: lata, lng: longa};
    }
    // If the user is searching by suburb, then we want to use the location data of the items to get the map centered on that suburb, since only those results will show.
    else if (searchedSuburbs) {
        var myLatLng = {lat: parseFloat(results[1]['latitude']), lng: parseFloat(results[1]['longitude'])};
    }
    // Lastly, if we aren't searching by location or suburb, then we have two options will allow for a large distance between items, so the results are centered on the user,
    // and the map is also zoomed out a bit.
    else {
        var myLatLng = {lat: lata, lng: longa};
        zoomLevel = 11;
    }

    // Create a new map element and center the map and give it a zoom
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: zoomLevel,
        center: myLatLng
    });

    // Check if the user is searching by location.  If they are we want to place a marker to indicate where we think they are and where we're basing the distances off.
    if (valueSearched == "My Location") {
        // Set the marker to the user's position and assign it to our map and give it a title "Your Position".
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: "Your Position"
        });
    }
  
    // Create an array to store all of the labels that we create in the next for loop
    map_marker_labels = [];
    // Loop through all of the elements in the "places_in_range" array (all elements that we want to display basically).
    for (var i = 0; i < places_in_range.length; i++) {
        // Get the latitude and longitude of the item and store it in the variable "myLatLng".
        var myLatLng = {lat: locs[places_in_range[i][1]][0], lng: locs[places_in_range[i][1]][1]};
        // So to simplify what happens in this if - else statement, basically it checks what letter it's up to, and if it get's to Z, then it'll update "labelIndex2" so that it knows
        // to add another letter in front from now on and the index of that varible determines the letter in front (0 = 'nothing', 1 = A, 2 = B, etc.).  These are stored in the variable
        // "abc" which is also pushed to the "map_marker_labels" array.
        if (labelIndex < 25) {
            if (labelIndex2 == 0) {
                abc = labels[labelIndex];
            }
            else {
                abc = labels[labelIndex2 - 1];
                abc += labels[labelIndex];
            }
            labelIndex++;
        }
        else {
            if (labelIndex2 == 0) {
                abc = labels[labelIndex];
            }
            else {
                abc = labels[labelIndex2 - 1];
                abc += labels[labelIndex];
            }
            labelIndex = 0;
            labelIndex2++;
        }

        map_marker_labels.push(abc);
        
        // Create an info content window.  This window will display when the user clicks on the marker on the map, displaying some information about the item connected that that particular marker.
        // This info content window displays the name of the item, the address of the item and the suburb of the item, and also has a link to the individual item page for that particular item.
        infoContent = "<div><strong>" + locs[places_in_range[i][1]][2] + "</strong><br>" + locs[places_in_range[i][1]][3] + ", " + locs[places_in_range[i][1]][4] + "<br><a href='item-results.php?hotspotId=" + (places_in_range[i][1] + 1) + "'>Read more</a></div>";
        
        // Create the info window and attach the content for the info window.
        infowindow = new google.maps.InfoWindow({
            content: infoContent
        });

        // Create a marker for the item the same as the location marker before, except this time we will give the marker a label which we computed before.
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: locs[places_in_range[i][1]][2],
            label: map_marker_labels[i],
            info: infoContent
        });

        // Create an event listener to check if any of the markers have been clicked and if so, display the corrsponding info window for the marker clicked.  It will also close any other open
        // info window so that there is only ever one info window open at a time.
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.close();
            infowindow.setContent(this.info);
            infowindow.open(map, this);
        });
    }

    // Get the elements on the page that have a class of "distanceObjects".
    var distanceObjects = document.getElementsByClassName("distanceObject");
    // Get the elements on the page that have a class of "hotspotNames".
    var hotspotNames = document.getElementsByClassName("hotspotNames");
    // Get the elements on the page that have a class of "addresses".
    var addresses = document.getElementsByClassName("addresses");
    // Get the elements on the page that have a class of "map-id".
    var map_id = document.getElementsByClassName("map-id");
    // Get the elements on the page that have a class of "references".
    var references = document.getElementsByClassName("references");
    // Get the elements on the page that have a class of "stars".
    var stars = document.getElementsByClassName("stars");

    // Iterate through the for loop for as many times as there are items being displayed.
    for (var i = 0; i < distanceObjects.length; i++) {
        // Assign each of the elements with a class of "distacneObjects" a distance with 1 decimal place in km.
        distanceObjects[i].innerHTML = (Math.round(places_in_range[i][0] * 10 / 1000) / 10) + " km away";
        // Assign each of the elements with a class of "map-id" a label the same as the marker that they are associated with.
        map_id[i].innerHTML = map_marker_labels[i];
        // Check if the user is seaching by location, and if so, there is a bit more work that needs to be done.
        if (valueSearched == "My Location") {
            // Assign each of the elements with a class of "hotspotNames" a name relating to the distance of the item (using the index position that was mentioned earlier).
            hotspotNames[i].innerHTML = locs[places_in_range[i][1]][2];
            // Assign each of the elements with a class of "addresses" an address relating to the distance of the item.
            addresses[i].innerHTML = locs[places_in_range[i][1]][3];
            // Assign each of the elements with a class of "references" a link relating to the item based on the distance of the item.
            references[i].href = "item-results.php?hotspotId=" + (places_in_range[i][1] + 1);
            // Check what the star rating for the item is, if it's 0, then it's a special case meaning that there are no reviews for it yet and we assign it a greyed-out star.
            // This is done by simply adding classes to the star elements and we let CSS do the rest for us.
            if (ratings[places_in_range[i][1] + 1] == 0) {
                for (var j = 0; j < 5; j++) {
                    stars[i * 5 + j].classList.add("no-reviews");
                    stars[i * 5 + j].classList.add("coloured");
                }
            }
            // If the rating of the item isn't 0, then we want our stars to display between 1 - 5 fully coloured stars and we want them to be gold.  We do this by simply leaving
            // out the class "no-reviews" and then the stars will be coloured depending on how many (out of 5) stars the place has on average.
            else {
                for (var j = 0; j < ratings[places_in_range[i][1] + 1]; j++) {
                    stars[i * 5 + j].classList.add("coloured");
                }
            }
        }
    }
}


// This function is called from the "getLocation" function early in the file.  This function is called when the user allows access to their location.  Basically it gets the current
// latitude and longitude coordinates of the user and then passes those to the other functions "get_distance" and "generateMap", which we saw earlier.
function getPosition (position) {
    var lata = position.coords.latitude;
    var longa = position.coords.longitude;

    get_distance(lata, longa);
    generateMap(lata, longa);
}


// This function does the same thing as the "getPosition" function, however the difference is that this function is called when there is an error in the "getLocation" function.  This
// is most likely caused by the user denying access to their location.  In this case, we have chosen to ignore the error message (single parameter for errors is required in the getLocation
// function) and instead just assume that the user in the middle of Brisbane City and have preset the coordinates for that and then pass those to the other functions.
function showBris (error) {
    var lata = -27.4698;
    var longa = 153.0251;

    get_distance(lata, longa);
    generateMap(lata, longa);
}


// Call the getLocation function so that everything can run at the start!
getLocation();