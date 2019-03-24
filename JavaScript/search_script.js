// This script is used to ensure that the footer of the page is at the bottom unless there is too much content on the page already.
// Basically this is for people who have 4K monitors like me.

//Now this process that I am about to describe will be very similar for a number of JavaScript files.  The difference between them
// is the numbers themselves, as each page has a different number and shape of elements, taking up different amounts of space.

// Get the current height of the window.
h = window.innerHeight;

// This function will force the footer to the bottom of the page if it isn't there already
function setUpScreen (dif) { 
    // Define some variables about heights of elements.
    var headerHeight = 60;
    var footerHeight = 240;
    var minSearchHeight = 620;

    // Check that the height of the window is greater than the total height of the elements (including the footer).  If this is
    // the case, then we know there is some free space for the footer to move down to.
    if (dif > minSearchHeight + headerHeight + footerHeight) {
        var len = dif - headerHeight - footerHeight;

        // Increase the size of the "banner" container to force the footer down.
        document.getElementById("banner").style.height = len + "px";
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