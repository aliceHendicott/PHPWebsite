<?php
	//reset the session
    session_start();
    session_destroy();

    // send the user back to the search page
    header("location: ../search-page.php");
?>