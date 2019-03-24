<?php
    //check if both items have been entered into the login
    if (isset($_POST['email']) && isset($_POST['password'])) {
        try {
            //pull email from input
            $email = $_POST['email'];
            //convert html characters to their html symbol entities
            $email = htmlspecialchars($email);
            //pull password from input
            $password = $_POST['password'];
            //convert html characters to their htmk symbol entities
            $password = htmlspecialchars($password);
            //pull the user details relating to the email used
            $sql = "SELECT id, firstName, lastName, password, salt FROM members WHERE email = :email";
            $statement = $database->prepare($sql);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $values = $statement->fetch(PDO::FETCH_ASSOC);
            $salt = $values['salt'];
            $pass_this = $values['password'];
            $pass = $password . $salt;

            //check if encrypted password matches entered password
            if (password_verify($pass, $pass_this)) {
                //set session variables to represent that a user is logged in and their details
                $_SESSION['loggedIn'] = true;
                $_SESSION['firstName'] = $values['firstName'];
                $_SESSION['lastName'] = $values['lastName'];
                $_SESSION['memberId'] = $values['id'];
                //send the user back to the search page
                header("location: search-page.php");
                die();
            }
            else {
                //if incorrect input entered output the error
                echo "<p class=\"registration-error\">The password you entered does not match the email you provided</p>";
            }
            
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
?>