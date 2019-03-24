// Get the current height of the window.
h = window.innerHeight;

// This function will force the footer to the bottom of the page if it isn't there already
function setUpScreen (dif) { 
    // Define some variables about heights of elements.
    var headerHeight = 60;
    var footerHeight = 240;
    var minResultHeight = 1018;

    // Check that the height of the window is greater than the total height of the elements (including the footer).  If this is
    // the case, then we know there is some free space for the footer to move down to.
    if (dif > minResultHeight + headerHeight + footerHeight - 150) {
        var len = dif - headerHeight - footerHeight - minResultHeight;

        // Increase the size of the "container" container to force the footer down.
        document.getElementById("container").style.height = len + "px";
    }
}

// Run the function as soon as the page loads.
setUpScreen(h);

// Create an event to check if the screensize changes.  If it does, reassess the height of the window and call the function
// "setUpScreen" again to make sure the footer still has enough space.
addEventListener("resize", function(event) {
    h = window.innerHeight;

    setUpScreen(h);
});


// The function "initMap" is used to create the map on the page.  
function initMap() {
    // This grabs the values of the latitude and longitude of the particular item which was grabbed earlier in the "grab-values.php"
    // file.  These values are then stored in myLatLng for later use.
    var myLatLng = { lat: latitude, lng: longitude };
  
    // Create the map for the page and give it a zoom level of 15 (pretty zoomed in) and center it on the item coordinates.
    var map = new google.maps.Map(document.getElementById('results-map'), {
      zoom: 15,
      center: myLatLng
    });
    
    // Create an info content window which will display the name of the item, and it's address and suburb, which, when the marker is clicked,
    // will be displayed.
    infoContent = "<div><strong>" + hotspotName + "</strong><br>" + address + ", " + suburb + "</div>";
        
    // Create the actual info window with the content created just above.
    infowindow = new google.maps.InfoWindow({
        content: infoContent
    });

    // Generate a marker at the location of the item and give it a title.
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: '7th Brigade Park, Chermside'
    });

    // Create an event to check if the marker is clicked, and if so, display the info window created earlier.
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
}