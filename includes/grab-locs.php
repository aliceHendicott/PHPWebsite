<?php
    // Please ignore the variable names here.  I ran out of ideas
    // Basically just initialise a vairable that is true unless told otherwise.  This is do prevent an error occuring
    // in the next if statement
    $yesNo = true;
    // Check if the variable "search_by_location" is set.  This variable is either true or false and says if the user
    // has searched based on their location or not.
    if (isset($_SESSION['search_by_location'])) {
        // Set the variable to either true or false.
        $yesNo = $_SESSION['search_by_location'];
    }

    // If the user hasn't searched by location then we need to get the values that were sent by the form on the search page.
    if (!$yesNo) {
        $selected_suburb = $_POST['suburbs'];
        $selected_name = $_POST['search_name'];
        $selected_rating = $_POST['ratings'];
    }
    // If they do search by location, then we still need to initialise the variables so we don't get errors.
    else {
        $selected_suburb = null;
        $selected_name = null;
        $selected_rating = null;
    }

    // Initialise variable
    $name_selection_details = null;

    // echo $selected_name;
    // Get the data from the database by searching based on the hotspot name if the user is searching by something
    // that isn't search by location.
    if ($selected_name != null) {
        try {
            // Prepare an SQL statement that gets all of the information relating to the hotspots that were searched
            $sql = "SELECT hotspotName, address, suburb, latitude, longitude  FROM items WHERE hotspotName = :hotspotName";

                // Prepare the SQL statement
                $statement = $database->prepare($sql);
                // Bind the value of the selected hotspot names to the statement
                $statement->bindValue(':hotspotName', $selected_name);
                // Execute the statement
                $statement->execute();
    
                // Fetch the results of the SQL query
                $name_selection_details = $statement->fetch(PDO::FETCH_ASSOC);       
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    // Now had to break up the next section into each column.  So this statement will grab every hotspot name from the
    // column hotspotName and store it in the variable hotspots
    try {
        $sql = "SELECT hotspotName FROM items";

            $statement = $database->prepare($sql);
            $statement->execute();

            $hotspots = $statement->fetchAll(PDO::FETCH_COLUMN);        
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }


    // This statement will grab every address from the column address in the items table and store it in the variable
    // addresses
    try {
        $sql = "SELECT address FROM items";

            $statement = $database->prepare($sql);
            $statement->execute();

            $addresses = $statement->fetchAll(PDO::FETCH_COLUMN);        
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }


    // This statement will grab every suburb from the column suburb in the items table and store it in the variable
    // suburb
    try {
        $sql = "SELECT suburb FROM items";

            $statement = $database->prepare($sql);
            $statement->execute();

            $suburbs = $statement->fetchAll(PDO::FETCH_COLUMN);        
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }


    // This statement will grab every latitude value from the column latitude in the items table and store it in the variable
    // lats
    try {
        $sql = "SELECT latitude FROM items";

            $statement = $database->prepare($sql);
            $statement->execute();

            $lats = $statement->fetchAll(PDO::FETCH_COLUMN);        
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }


    // This statement will grab every logitude value from the column logitude in the items table and store it in the variable
    // longs
    try {
        $sql = "SELECT longitude FROM items";

            $statement = $database->prepare($sql);
            $statement->execute();

            $longs = $statement->fetchAll(PDO::FETCH_COLUMN);    
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

?>

<!-- This will convert all of the PHP variables to JavaScript variables so that they can be used in JavaScript a little later. -->
 <script type="text/javascript">
    // Convert each of the PHP arrays to JavaScript arrays using json_encode.  This is then echoed so that it is inserted into
    // JavaScript as text.  Since this is an array, we don't want to put quotes around the PHP tags.
    var hotspots = <?php echo json_encode($hotspots); ?>;
    var addresses = <?php echo json_encode($addresses); ?>;
    var suburbs = <?php echo json_encode($suburbs); ?>;
    var lats = <?php echo json_encode($lats); ?>;
    var longs = <?php echo json_encode($longs); ?>;

    var name_selection_details = <?php echo json_encode($name_selection_details); ?>;
</script> 