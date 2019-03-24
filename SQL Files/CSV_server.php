<!DOCTYPE html>
<html>
<head>

</head>

<body>
<?php
    // This file generates all of the tables for the database and also grabs data from CSV files to populate the
    // items table and the reviewsRating table.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $time = microtime(TRUE);
    $mem = memory_get_usage();
    
    // Assign variables needed to connect to the database
    $host = "cab230.sef.qut.edu.au";
    $username = "n9718681";
    $password = "CAB230";
    $dbName = "n9718681";

    // Connect to the database server and create new database
    try {
        $database = new PDO("mysql:host=$host;dbname=", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sql = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8 COLLATE utf8_general_ci;";
    
        $statement = $database->prepare($sql);
        $statement->execute();
    
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Create table items in the database
    try {
        $database = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    try {
        $sql = "CREATE TABLE IF NOT EXISTS items
        (hotspotId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        hotspotName varchar(100) UNIQUE NOT NULL,
        address VARCHAR(250) NOT NULL,
        suburb VARCHAR(100) NOT NULL,
        latitude DECIMAL(15,11) NOT NULL,
        longitude DECIMAL(15,11) NOT NULL)
        CHARACTER SET utf8 COLLATE utf8_general_ci;";

        $statement = $database->prepare($sql);
        $statement->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Create table members in the database
    try {
        $sql = "CREATE TABLE IF NOT EXISTS members
        (id INT(10) UNIQUE NOT NULL AUTO_INCREMENT PRIMARY KEY,
        email varchar(100) UNIQUE NOT NULL,
        firstName VARCHAR(40) NOT NULL,
        lastName VARCHAR(40) NOT NULL,
        dob DATE NOT NULL,
        password VARCHAR(100) NOT NULL,
        salt VARCHAR(20) NOT NULL)
        CHARACTER SET utf8 COLLATE utf8_general_ci;";

        $statement = $database->prepare($sql);
        $statement->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Create table reviews in the database
    try {
        $sql = "CREATE TABLE IF NOT EXISTS reviews
        (reviewId INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        memberId INT(10) NOT NULL REFERENCES members(id),
        hotspotId INT(10) UNSIGNED NOT NULL REFERENCES items(hotspotId),
        rating INT(1) NOT NULL,
        reviewDate DATE NOT NULL,
        review VARCHAR(2000) NOT NULL)
        CHARACTER SET utf8 COLLATE utf8_general_ci;";

        $statement = $database->prepare($sql);
        $statement->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Create table reviewsRating in database
    try {
        $sql = "CREATE TABLE IF NOT EXISTS reviewsRating
        (hotspotId INT(10) UNSIGNED NOT NULL REFERENCES items(hotspotId),
        averageRating DECIMAL(15,11) NOT NULL,
        numReviews INT(4) NOT NULL)
        CHARACTER SET utf8 COLLATE utf8_general_ci;";

        $statement = $database->prepare($sql);
        $statement->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    print_r(array(
        'memory' => (memory_get_usage() - $mem) / (1024 * 1024) . " MB",
        'seconds' => microtime(TRUE) - $time
    ));

    echo "<br>";

    // Open the CSV file that contains data on all of the hotspots, with read permissions
    $file = fopen("CAB230-2018-Dataset-wifi-hot-spots-win.csv", "r");
    // Set counter variable i to 0
    $i = 0;
    // Run while loop so that it will continue running until it runs out of lines to read in the CSV file.
    while (!feof($file)) {
        // var_dump(fgetcsv($file));
        // echo "<br>";
        // Push current rows data into each array based on column
        list($wfName[], $address[], $suburb[], $lat[], $long[]) = fgetcsv($file);
        // echo $wfName[$i];
        // $i = $i + 1;
        // The if statement is so that we don't store the titles in the database.  So we don't do anything on the first run through.
        if ($i > 0) {
            // Insert the data into the items table in the database
            try {
                $sql = "INSERT IGNORE INTO items (hotspotName, address, suburb, latitude, longitude)
                    VALUES (:hotspotName, :address, :suburb, :latitude, :longitude)";

                $statement = $database->prepare($sql);

                $statement->bindValue(':hotspotName', $wfName[$i]);
                $statement->bindValue(':address', $address[$i]);
                $statement->bindValue(':suburb', $suburb[$i]);
                $statement->bindValue(':latitude', $lat[$i]);
                $statement->bindValue(':longitude', $long[$i]);

                $statement->execute();
            }
            catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        echo $i;
        echo "<br>";
        $i++;
    }
    // Close the file
    fclose($file);

    // $file = fopen("CAB230-reviews.csv", "r");
    // $i = 0;
    // while (!feof($file)) {
    //     // var_dump(fgetcsv($file));
    //     // echo "<br>";
    //     list($fName[], $lName[], $wfName[], $rating[], $review_date[], $review[]) = fgetcsv($file);
    //     // echo $wfName[$i];
    //     // $i = $i + 1;
    //     if ($i > 0) {
    //         try {
    //             $sql = "INSERT IGNORE INTO reviews (firstName, lastName, hotspotName, rating, reviewDate, review)
    //                 VALUES (:firstName, :lastName, :hotspotName, :rating, :reviewDate, :review)";

    //             $statement = $database->prepare($sql);

    //             $statement->bindValue(':firstName', $fName[$i]);
    //             $statement->bindValue(':lastName', $lName[$i]);
    //             $statement->bindValue(':hotspotName', $wfName[$i]);
    //             $statement->bindValue(':rating', $rating[$i]);
    //             $statement->bindValue(':reviewDate', $review_date[$i]);
    //             $statement->bindValue(':review', $review[$i]);

    //             $statement->execute();
    //         }
    //         catch (PDOException $e) {
    //             echo $e->getMessage();
    //         }
    //     }
    //     echo $i;
    //     echo "<br>";
    //     $i++;
    // }
    // fclose($file);

    // Open another CSV file that stores all of the initial values for the reviewsRating table
    $file = fopen("cab230-ratings.csv", "r");
    $i = 0;
    // While there is still data to read, keep running
    while (!feof($file)) {
        // var_dump(fgetcsv($file));
        // echo "<br>";
        // Store the data from each column in the line into each of the arrays
        list($wfId[], $rating[], $numRatings[]) = fgetcsv($file);
        // echo $wfName[$i];
        // $i = $i + 1;
        // Don't do anytyhing with the title of each column
        if ($i > 0) {
            // Insert the data into the database under the reviewsRating table
            try {
                $sql = "INSERT IGNORE INTO reviewsrating (hotspotId, averageRating, numReviews)
                    VALUES (:hotspotId, :averageRating, :numReviews)";

                $statement = $database->prepare($sql);

                $statement->bindValue(':hotspotId', $wfId[$i]);
                $statement->bindValue(':averageRating', $rating[$i]);
                $statement->bindValue(':numReviews', $numRatings[$i]);

                $statement->execute();
            }
            catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        // echo $i;
        // echo "<br>";
        $i++;
    }
    // Close the file
    fclose($file);
?>

</body>