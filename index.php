<?php
require 'config.php';

password_protect();

//Connect to MySQL 
$pdo = pdo_connect_mysql();

//Query that selects all the runs from our database per athlete
$stmt = $pdo->prepare('SELECT * FROM runs where athlete_id = ?');
$stmt->execute([$_SESSION['id']]);
$runs = $stmt->fetchAll(PDO::FETCH_ASSOC);

//DASHBOARD REPORTING
//calculating total miles
$total_miles =  0;
foreach ($runs as $run) {
    $total_miles += $run['distance'];
}

//calculating total time running 
$total_time = 0;
foreach ($runs as $run) {
    $total_time += $run['time_elapsed'];
}
$total_time = gmdate("H:i:s", $total_time);

//calculating # of runs
$total_runs = sizeof($runs);

//sort runs from most recent to oldest using custom sorting function
usort($runs, "date_sort");
//limit array to only 5 results
$runs = array_slice($runs, 0, 5);

//Query to get the athlete's username for display
$stmt = $pdo->prepare('SELECT username FROM athletes where id = ?');
$stmt->execute([$_SESSION['id']]);
$username = $stmt->fetchColumn();


?>

<?= header_template() ?>
<?= nav_template() ?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4 text-center">Great running, <?= $username ?>!</h1>
        <div class="row py-3">
            <div class="col">
                <h2 class="text-center">Total Miles: <?= $total_miles ?></h2>
            </div>
            <div class="col">
                <h2 class="text-center">Time Running: <?= $total_time ?></h2>
            </div>
            <div class="col">
                <h2 class="text-center"># of Runs: <?= $total_runs ?></h2>
            </div>
        </div>
    </div>
    <div class="container text-center">
        <a href="add-run-form.php" class="btn btn-primary text-center">Add New Run</a>
    </div>

</div>

<div class="container">
    <h2 class="text-center">Recent Runs</h2>
    <div style="overflow-x:scroll;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Distance</th>
                    <th scope="col">Pace</th>
                    <th scope="col">Time Elapsed</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($runs as $run) :
                    //calculating pace for each run
                    $seconds_per_mile = $run['time_elapsed'] / $run['distance'];
                    $mins = intdiv($seconds_per_mile, 60);
                    $secs = $seconds_per_mile % 60;
                    //check length of seconds to determine if we need a leading zero 
                    if (strlen(strval($secs)) == 1) {
                        $secs = "0" . $secs;
                    }
                    $pace = $mins . ":" . $secs . " /mi";

                    //converting datetime from MySQL into format with no HH:MI:SS 
                    $date = strtotime($run['date']);
                    $formattedDate = date("m/d/y", $date);
                ?>


                    <tr>
                        <td>
                            <?= $run['title'] ?>
                        </td>
                        <td>
                            <?= $run['distance'] ?>
                        </td>
                        <td>
                            <?= $pace ?>
                        </td>
                        <td>
                            <?= gmdate("H:i:s", $run['time_elapsed']) ?>
                        </td>
                        <td>
                            <?= $formattedDate ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="container text-center pb-3">
             <a class="text-info" href="run-list.php">View All Runs</a>
        </div>
    </div>

</div>


<?= footer_template() ?>