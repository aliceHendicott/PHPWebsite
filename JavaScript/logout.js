// This is a simple JavaScript file that's sole purpose is for when the user is logged in, they can be logged out again.
// When the user hovers the mouse over their name in the top right corner, the function "logout_overlay" is called.  This
// function will display the logout overlay.  It will also darken the background colour of the user's name while the overlay
// is active.  The overlay is displayed by changing the visibility to "visible".
function logout_option () {
	var logout_overlay = document.getElementById("logout_overlay");
	logout_overlay.style.visibility = "visible";
	var user = document.getElementById("userName");
	user.style.backgroundColor = "#3B1261";
}

// When the users moves the mouse outside of the users name, then this function is called to hide the logout overlay again
// so that it isn't permanently there.  This is done by making the visibility of the element hidden and also changing the
// background colour of the user's name the same colour as the rest of the header again.
function hide_logout_option(){
	var logout_overlay = document.getElementById("logout_overlay");
	logout_overlay.style.visibility = "hidden";
	var user = document.getElementById("userName");
	user.style.backgroundColor = "#551A8B";
}