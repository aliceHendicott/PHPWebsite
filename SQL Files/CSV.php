<!DOCTYPE html>
<html>
<head>

</head>

<body>
    <!-- This file is not relevant to the finished product, but used to set up the local servers for testing purposes. -->
    <!-- Please look at CSV_server.php for how we set up the final server database. -->
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $time = microtime(TRUE);
    $mem = memory_get_usage();
    
    $host = "localhost";
    $username = "test";
    $password = "cab230";
    $dbName = "cab230_assignment";

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

    $file = fopen("CAB230-2018-Dataset-wifi-hot-spots-win.csv", "r");
    $i = 0;
    while (!feof($file)) {
        // var_dump(fgetcsv($file));
        // echo "<br>";
        list($wfName[], $address[], $suburb[], $lat[], $long[]) = fgetcsv($file);
        // echo $wfName[$i];
        // $i = $i + 1;
        if ($i > 0) {
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

    $file = fopen("cab230-ratings.csv", "r");
    $i = 0;
    while (!feof($file)) {
        // var_dump(fgetcsv($file));
        // echo "<br>";
        list($wfId[], $rating[], $numRatings[]) = fgetcsv($file);
        // echo $wfName[$i];
        // $i = $i + 1;
        if ($i > 0) {
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
        echo $i;
        echo "<br>";
        $i++;
    }
    fclose($file);
?>

</body>