<?php
require 'config.php';

password_protect();
// Connect to MySQL 
$pdo = pdo_connect_mysql();

if (isset($_GET['id'])) {
    //select the record that is going to be deleted 
    $stmt = $pdo->prepare('SELECT * FROM runs WHERE id = ?'); 
    $stmt->execute([$_GET['id']]);
    $run = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$run) {
        $user_feedback->set_feedback('Run does not exist.', 'run-list', 'danger'); 
    }

    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            //User clicked the "yes" button, delete the record
            $stmt = $pdo->prepare('DELETE FROM runs WHERE id = ?');
            $stmt->execute([$_GET['id']]);  
            //output message
            $user_feedback->set_feedback('Successfully deleted run!', 'run-list', 'success'); 
        } else {
            //user clicked the "no" button, redirect them back to the home/index page
            header('Location: run-list.php'); 
            exit;
        } 
    }

} else {
    $user_feedback->set_feedback('No run was specified.', 'index', 'danger'); 
}
?>

<?= header_template('Delete Blog Post') ?>
<?= nav_template() ?>

<!-- START PAGE CONTENT -->
<?php if ($user_feedback->msg and $user_feedback->route) : ?>
    <?= $user_feedback->template_feedback() ?>
<?php endif; ?>
<div class="container py-5">
    <?php if (!$user_feedback->msg and !$user_feedback->route) : ?>    
    <h3 class="subtitle text-center">Are you sure you want to delete <?= $run['title'] ?>?</h3>
    <div class="container text-center">
        <a class="btn btn-success" href="delete-run.php?id=<?= $run['id'] ?>&confirm=yes">Yes</a>
        <a class="btn btn-danger" href="delete-run.php?id=<?= $run['id'] ?>&confirm=no">No</a>
    </div>

    <!-- END PAGE CONTENT -->
<?php endif; ?>   
</div>

<?= footer_template() ?>