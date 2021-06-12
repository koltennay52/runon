<?php

require 'config.php';
 

?>

<?= header_template('Login') ?>
<?= nav_template() ?>

<div class="container p-3">
    <h1 class="text-center">Login</h1>
    <form action="authenticate.php" method="post">
    <!-- Email input -->
    <div class="form-outline mb-4">
        <input type="text" id="form1Example1" class="form-control" name="username" />
        <label class="form-label" for="form1Example1">Username</label>
    </div>

    <!-- Password input -->
    <div class="form-outline mb-4">
        <input type="password" id="form1Example2" class="form-control" name="password"/>
        <label class="form-label" for="form1Example2">Password</label>
    </div>

    <!-- 2 column grid layout for inline styling -->
    <div class="row mb-4">
        <div class="col">
        <!-- Simple link -->
        <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div> 



<?= footer_template() ?>