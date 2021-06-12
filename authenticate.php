<?php
//var_dump($_POST);
require 'config.php';
session_start();

if (!isset($_POST['username'], $_POST['password'])) {
    //Could not get the data that should have been sent.  
    $user_feedback->set_feedback('Please fill out both username and password fields.', 'login', 'danger'); 

}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection. 
if ($stmt = $con->prepare('SELECT id, password FROM athletes WHERE username = ?')) {
    //Bind Parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s" )
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();

    //Store the result so we can check if the account exists in the database
    $stmt->store_result();

    //Authenticate the user
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id,$password);
        $stmt->fetch();
        //Account exists, now we can verify the password.  
        //Note: remember to use password_hash in your registration file to store the hashed passwords. 
        if (password_verify($_POST['password'],$password)) {
            //Verification success! User has logged-in! 
            //Create sessions, so we know the user is logged in.  This acts like cookies but remember the data on the server. 
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id; 
            header('Location: index.php');
        } else {
            $user_feedback->set_feedback('Incorrect password.', 'login', 'danger'); 

        }
    } else {
        $user_feedback->set_feedback('Incorrect username.', 'login', 'danger'); 
    }

    $stmt->close();

}

?>

<?= header_template('Login') ?>
<?= nav_template() ?>

<!-- message sent confirmation goes here -->
<?php if ($user_feedback->msg and $user_feedback->route) : ?>
    <?= $user_feedback->template_feedback() ?>
<?php endif; ?>

<?= footer_template() ?>