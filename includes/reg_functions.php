<?php
    $errors = [];
    //display error messages
    function error_message (&$errors, $message) {
        array_push($errors, $message);
    }

    //function determines if a string only contains letters and white space
    function char_only ($string) {
        global $errors;
        if (!preg_match("/^[a-zA-Z ]*$/", $string)) {
            error_message($errors, "Only letters and white space allowed");
            return false;
        }
        else {
            return true;
        }
    }

    //validates email according to correct format of an email
    function validate_email ($email) {
        global $errors;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_message($errors, "Invalid email format");
            return false;
        }
        else {
            return true;
        }
    }
    
    //function that validates first name - must have at least two letters and be only characters
    function validate_first_name ($string) {
        global $errors;
        if (strlen($string) >= 2) {
            if(char_only($string)) {
				return true;
			}
			else {
				return false;
			}
        }
        else {
            error_message($errors, "First name must be at least two characters long");
            return false;
        }
    }

    //function that validates last name - must have at least two letters and be only characters
    function validate_last_name ($string) {
        global $errors;
        if (strlen($string) >= 2) {
            if(char_only($string)) {
				return true;
			}
			else {
				return false;
			}
        }
        else {
            error_message($errors, "Last name must be at least two characters long");
            return false;
        }
    }

    //function to validate password, must match the pattern entered in regex, such that there must be a lower case letter, a capital letter,
    //a number, minumum 8 characters and maximum 30 characters
    function validate_password ($string) {
        global $errors;
        if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*[^a-zA-Z\d:]).{8,30}/", $string)) {
            error_message($errors, "Password does not meet requirements");
            return false;
        }
        else {
            return true;
        }
    }
?>