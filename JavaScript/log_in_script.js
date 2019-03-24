// Get the current height of the window.
h = window.innerHeight;

// Create a function that will set up the screen correctly so that the footer will appear at the bottom of the page.
function setUpScreen (dif) { 
	// Define heights of elements on the page.
    var headerHeight = 60;
    var footerHeight = 240;
    var minLogHeight = 426;

	// Check that the height of the page is larger than that of the total height of the elemtents and if it is, then
	// we know that there is some free space for the footer to move down.
    if (dif > minLogHeight + headerHeight + footerHeight) {
        var len = dif - headerHeight - footerHeight - 50;

		// Force the footer to the bottom by increasing the height of the "log-in-container".
        document.getElementById("log-in-container").style.height = len + "px";
    }
}

// Run the function so that it sets up the screen straight away.
setUpScreen(h);

// Create an event listener to check if the screen height changes, and if it does, call the "setUpScreen" function again
// to check if there is still space to push the footer down to, and if not, then the footer will act as normal.
addEventListener("resize", function(event) {
    h = window.innerHeight;

    setUpScreen(h);
});