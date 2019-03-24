<?php
    require_once("reg_functions.php");

    $t4 = false;
    //validate the first name entered
    if (isset($_POST['firstname'])) {
        $firstname = $_POST['firstname'];
        $firstname = htmlspecialchars($firstname);
        validate_first_name($firstname);
    }

    //validate the last name entered
    if (isset($_POST['lastname'])) {
        $lastname = $_POST['lastname'];
        $lastname = htmlspecialchars($lastname);
        validate_last_name($lastname);
    }

    //vaidate the email entered
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $email = htmlspecialchars($email);
		validate_email($email);
    }

    //validate the password
    if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $password_ori = $_POST['password'];
        $password_ori = htmlspecialchars($password_ori);
        $password_con = $_POST['confirm_password'];
        $password_con = htmlspecialchars($password_con);
        //confirm passwords are valid and that they match
        if (validate_password($password_ori) && validate_password($password_con)) {
            if ($password_ori == $password_con) {
                $t4 = true;
            }
            else {
                $t4 = false;
                echo '<span class="registration-error" id="password-error">Passwords do not match</span><br />';
            }
        }
        else {
            $t4 = false;
            echo '<span class="registration-error" id="password-error">Please enter a valid password</span><br />';
        }
    }

    global $errors;
    if (count($errors) == 0 && $t4) {
        try {
            //insert new registered user deails into the database
            $sql = "INSERT IGNORE INTO members (email, firstName, lastName, dob, password, salt)
                    VALUES (:email, :firstName, :lastName, :dob, :password, :salt)";

            $statement = $database->prepare($sql);

            // Randomly generate a 20 character long salt that will beappended to the password to help
            // encrypt the password for storage on the server
            $salt = substr(md5(uniqid()), 0, 20);
            $password = $_POST['password'] . $salt;
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Bind all of the variables
            $statement->bindValue(':email', $email);
            $statement->bindValue(':firstName', $firstname);
            $statement->bindValue(':lastName', $lastname);
            $statement->bindValue(':dob', $_POST['dob']);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':salt', $salt);

            $statement->execute();

            //set logged in as true and assign session variables for the users details
            $_SESSION['loggedIn'] = true;
            $_SESSION['firstName'] = $firstname;
            $_SESSION['lastName'] = $lastname;

            //pull members id from the database
            $sql = "SELECT id FROM members WHERE email = :email";
            $statement = $database->prepare($sql);
            $statement->bindValue(':email', $email);

            $statement->execute();

            $values = $statement->fetch(PDO::FETCH_ASSOC);

            //set up a session variable for the members ID
            $_SESSION['memberId'] = $values['id'];

            //relocate the user to the search page
            header("location: search-page.php");
            exit();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    else {
        foreach($errors as $error){
            echo "<span class=\"registration-error\">$error</span><br />";
        }
    }
?>