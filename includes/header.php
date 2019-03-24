<div id="header">
    <!-- Setting up Hotspot logo in the header -->
    <div id="header-logo"><a href="search-page.php"><img src="images/logo.png" alt="Hotspot.com"/></a></div>
    <!-- Setting up the different page links in the header -->
    <div class="menu">
        <a href="search-page.php">Search</a>
        <?php if (!isset($_SESSION['loggedIn'])) { ?>
        <a id="loginheader" href="log-in-page.php">Log in</a>
        <a id="registerheader" href="registration-page.php">Register</a>
        <?php } else { ?>
        <div class="menu" id="menu_2Levels">
        <!-- Display the users name if they are logged in -->
        <a href="javascript: ;" onmouseover="logout_option()" onmouseout="hide_logout_option()" id="userName"><?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?></a>
        <div id="logout_overlay" onmouseover="logout_option()"  onmouseout="hide_logout_option()"><a id="logout" href="includes/logout.php">Log Out</a></div>
    </div>
        <?php } ?>
    </div>
</div>