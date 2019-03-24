<?php
	//include session variable to start the session
	require "includes/session.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			//include the CSS stylesheet
			require "includes/CSS_link.php";
		?>
		<!--set up title for the page-->
		<title>hotspot.com - Log In</title>
		<meta charset="utf8" />
		<meta name="author" content="Cameron Murray, Bradley Park & Alice Hendicott"/>
		<meta name="description" content="Hotspot Login" />
		<meta name="keywords" content="Hotspot, Login" />
	</head>
	<body>
        <?php
        	//set up the header
			require_once("includes/header.php");
			//this file handles the validation of the login form
			require_once("includes/login.php");
        ?>
        <!--set up the log in form-->
		<div id="log-in-container">
			<div id="log-in" class="registration">
				<h1>Log In</h1>
				<form name="log_in_details" action="log-in-page.php" method="post">
					<input name="email" type="email" class="search-item text-item" placeholder="Email"></input>
					<input name="password" type="password" class="search-item text-item" placeholder="Password"></input>
					<input name="login" class="search-button" type="submit" value="Log In">
				</form>
			</div>
		</div>
		<!-- set up the footer-->
		<?php
             include("includes/footer.php");
        ?>
        <!-- link to the javascript file for this page-->
		<script language="javascript" type="text/javascript" src="JavaScript/log_in_script.js"></script>
	</body>
</html>