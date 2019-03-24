<?php
	require "includes/session.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- link to CSS stylesheet-->
		<?php
			require "includes/CSS_link.php";
		?>
		<!-- set up title for the registration page-->
		<title>hotspot.com - Register Account</title>
		<meta charset="utf8" />
		<meta name="author" content="Cameron Murray, Bradley Park & Alice Hendicott"/>
		<meta name="description" content="Hotspot Registation Page" />
		<meta name="keywords" content="Hotspot, Registation" />
		<meta name="robots" content="noindex" />
	</head>
	<body>
        <?php
        	//add the header to the page
			require_once("includes/header.php");
			//require the form which handles the registration form validation
			require_once("includes/registration.php");
        ?>
        <!--Set up registration form -->
		<div id="registration-container">
			<div id="registration" class="registration">
				<h1>Register Account</h1>
				<form id="registration_details" name="registration_details" action="registration-page.php" method="post">
					<input name="firstname" type="text" class="search-item text-item" placeholder="First Name" value="<?= $_POST['firstname'] ?? ''; ?>" required />
					<input name="lastname" type="text" class="search-item text-item" placeholder="Last Name" value="<?= $_POST['lastname'] ?? ''; ?>" required />
					<input name="email" type="email" class="search-item text-item" placeholder="Email" value="<?= $_POST['email'] ?? ''; ?>" required />
					<input id="dob" name="dob" class="search-item text-item" type="date" placeholder="Date of Birth" value="<?= $_POST['dob'] ?? ''; ?>" required />
					<input id ="password" name="password" type="password" class="search-item text-item" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" onkeyup="check_valadation()" required>
					<input id ="confirm_password" name="confirm_password" type="password" class="search-item text-item" placeholder="Confirm Password" onkeyup="check_valadation()" >
					<input name="register" class="search-button" type="submit" value="Register" />
				</form>
				<!-- set up password error box which displays whether or not the requirements of the password have been met-->
				<div id="not_valid_format" class="valid_format">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum of<b> 8 characters</b></p>
					<p id="length2" class="valid">Maximum of<b> 30 characters</b></p>
					<p id="match" class="invalid"> <b>Passwords must Match </b></p>
				</div>
			</div>
		</div>
		<!--set up footer-->
		<?php
            include("includes/footer.php");
        ?>
        <!-- link to javascript file-->
		<script language="javascript" type="text/javascript" src="JavaScript/registration_script.js"></script>
	</body>
</html>