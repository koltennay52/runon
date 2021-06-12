<?php

require 'config.php';
 
// Now we we check if the data was submitted, isset() function will check if the data exists
if (isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    // We need to check if the account with that username exists
        if ($stmt = $con->prepare('SELECT id, password FROM athletes WHERE username = ?')) {
            // Bind parameters (s=string, i = int, etc) has the password using the PHP password_hash function
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                //Username already exists
                $user_feedback->set_feedback('Username already exists.', 'login', 'danger');
            } else {
                //username doesn't exist, insert new account
                if ($stmt = $con->prepare('INSERT INTO athletes (username, password, email) VALUES (?,?,?)')) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $uniqid = uniqid();
                    $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
                    $stmt->execute();
                    $user_feedback->set_feedback('Account registered!', 'login', 'success');
                } else {
                    //something is wrong with the sql statement, check to make sure the accounts table exists with all 3 fields
                    $user_feedback->set_feedback('Account registration failed.', 'login', 'danger');
                }
            }
            $stmt->close();
        } else {
            $user_feedback->set_feedback('Account registration failed.', 'login', 'danger');
        }
    
        $con->close();
    
    }
    



?>

<?= header_template('Register') ?>
<?= nav_template() ?>

<div class="container p-3">
<!-- message sent confirmation goes here -->
<?php if ($user_feedback->msg and $user_feedback->route) : ?>
    <?= $user_feedback->template_feedback() ?>
<?php endif; ?> 
    <h1 class="text-center">Register</h1>
    <form action="" method="post">
    <!-- Username input -->
    <div class="form-outline mb-4">
        <input type="text" id="username" class="form-control" name="username" />
        <label class="form-label" for="username">Username</label>
    </div>

    <!-- Password input -->
    <div class="form-outline mb-4">
        <input type="password" id="password" class="form-control" name="password"/>
        <label class="form-label" for="password">Password</label>
    </div>

    <!-- Email input -->
    <div class="form-outline mb-4">
        <input type="email" id="email" class="form-control" name="email" />
        <label class="form-label" for="email">Email</label>
    </div>


    <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
</div> 



<?= footer_template() ?>