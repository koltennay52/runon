<?php
require 'config.php';

password_protect();

// Connect to MySQL 
$pdo = pdo_connect_mysql();

//check if POST Data is not empty 
if (!empty($_POST)) {


    $time_elapsed = 0;

    //calculate time elapsed from hours, minutes, seconds fields if those fields have been set
    if (isset($_POST['hours']) && isset($_POST['minutes']) && isset($_POST['seconds'])) {
         //convert hours to seconds
         $hours_converted = $_POST['hours'] * 3600;
         //convert minutes to seconds 
         $minutes_converted = $_POST['minutes'] * 60;
         //add hours, minutes, and seconds together
         $time_elapsed = $hours_converted + $minutes_converted + $_POST['seconds'];
    }
       

    //check to see if the data from the form is set 
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $distance = isset($_POST['distance']) ? $_POST['distance'] : 0;
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d H:i:s');

    //insert the new run record into the runs table
    $stmt = $pdo->prepare('INSERT INTO runs VALUES (NULL, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$_SESSION['id'], $title, $description, $distance, $time_elapsed, $date]); 

    //feedback for successful insert
    $user_feedback->set_feedback('Successfully added run!', 'index', 'success');

}

?>

<?= header_template('Add Run') ?>
<?= nav_template() ?>


<!-- START PAGE CONTENT -->

<!-- message sent confirmation goes here -->
<?php if ($user_feedback->msg and $user_feedback->route) : ?>
    <?= $user_feedback->template_feedback(); ?>
<?php endif; ?>
<div class="container">
<h1 class="title text-center pt-3">Add Run</h1>
    <form action="" method="post">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" placeholder=""></textarea>
    </div>

    <div class="form-group">
        <label for="distance">Distance</label>
        <input type="number" step="any" class="form-control" id="description" name="distance" placeholder="0.00">
    </div>

    <div class="form-group container">
        <h4 class="text-center pt-3">Time Elapsed</h4>
        <div class="row"> 
            <div class="col">
                <label for="hours">Hours</label>
                <input type="number" class="form-control" id="hours" name="hours" placeholder="0">
            </div>
            <div class="col">
                <label for="minutes">Minutes</label>
            <input type="number" class="form-control" id="minutes" name="minutes" placeholder="0">
            </div>
            <div class="col">
                <label for="seconds">Seconds</label>
                <input type="number" class="form-control" id="seconds" name="seconds" placeholder="0">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" placeholder="">
    </div>

    <div class="container pb-5 text-center">
        <input type="submit" class="btn btn-primary" value="Add Run">
        <a href="index.php" class="btn btn-light">Cancel</a>
    </div>

    </form>
</div>


<!-- END PAGE CONTENT -->

<?= footer_template() ?>