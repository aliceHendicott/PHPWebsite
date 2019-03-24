<?php
    //start the session
    session_start();
    
    // Set erros to display
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //set up PDO connection to the database
    // $host = "cab230.sef.qut.edu.au";
    // $username = "n9718681";
    // $password = "CAB230";
    // $dbName = "n9718681";
    $host = "localhost";
    $username = "test";
    $password = "cab230";
    $dbName = "cab230_assignment";

    // Establish connection with the database
    try {
        $database = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
?>