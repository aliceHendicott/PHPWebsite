// Get the current height of the window.
h = window.innerHeight;

// Create a function that will set up the screen correctly so that the footer will appear at the bottom of the page.
function setUpScreen (dif) { 
	// Define heights of elements on the page.
    var headerHeight = 60;
    var footerHeight = 240;
    var minRegHeight = 664;

	// Check that the height of the page is larger than that of the total height of the elemtents and if it is, then
	// we know that there is some free space for the footer to move down.
    if (dif > minRegHeight + headerHeight + footerHeight + 50) {
        var len = dif - headerHeight - footerHeight - 50;

		// Force the footer to the bottom by increasing the height of the "registration-container".
        document.getElementById("registration-container").style.height = len + "px";
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


var myInput = document.getElementById("password");
var myInput2 = document.getElementById("confirm_password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
var length2 = document.getElementById("length2");
var not_valid_format = document.getElementById("not_valid_format");



theymatch = false;
lowervalue = false;
uppervalue = false;
numbervalue = false;
lengthvalue = false;
lengthvalue2 = true;


myInput.onfocus = function() {
    not_valid_format.classList.remove("valid_format");
    not_valid_format.classList.add("invalid_format");
}


myInput.onblur = function() {
  	not_valid_format.classList.remove("invalid_format");
	not_valid_format.classList.add("valid_format");
}

myInput2.onfocus = function() {
    not_valid_format.classList.remove("valid_format");
    not_valid_format.classList.add("invalid_format");
}

myInput2.onblur = function() {
  	not_valid_format.classList.remove("invalid_format");
	not_valid_format.classList.add("valid_format");
}

function check_valadation() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  
  if(myInput.value.match(lowerCaseLetters)) { 
    letter.classList.remove("invalid");
    letter.classList.add("valid");
	lowervalue = true;
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
	lowervalue = false;
	
}

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) { 
    capital.classList.remove("invalid");
    capital.classList.add("valid");
	uppervalue = true;
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
	uppervalue = false;
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) { 
    number.classList.remove("invalid");
    number.classList.add("valid");
	numbervalue = true 
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
	numbervalue = false;
  }

  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
	lengthvalue = true;
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
	lengthvalue = false;
  }
  
    // Validate 2nd length
  if(myInput.value.length <= 30) {
    length2.classList.remove("invalid");
    length2.classList.add("valid");
	lengthvalue2 = true;
  } else {
    length2.classList.remove("valid");
    length2.classList.add("invalid");
	lengthvalue2 = false;
  }

	// Validateing that the passwords match
    if((myInput.value == myInput2.value) && (myInput.value.length > 0)) {
    match.classList.remove("invalid");
    match.classList.add("valid");
	theymatch = true;

  } else {
    match.classList.remove("valid");
    match.classList.add("invalid");
	theymatch = false;
  }
  // Hides not-valid-format box 
    if(lengthvalue && numbervalue && uppervalue && lowervalue && lengthvalue2 && theymatch ) {
		not_valid_format.classList.remove("invalid_format");
		not_valid_format.classList.add("valid_format");
  } else {
    not_valid_format.classList.remove("valid_format");
    not_valid_format.classList.add("invalid_format");	
  } 
}