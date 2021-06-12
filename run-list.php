<?php
require 'config.php';

password_protect();

//Connect to MySQL 
$pdo = pdo_connect_mysql();

//Query that selects all the runs from our database per athlete
$stmt = $pdo->prepare('SELECT * FROM runs where athlete_id = ?');
$stmt->execute([$_SESSION['id']]);
$runs = $stmt->fetchAll(PDO::FETCH_ASSOC);


//checking submissions for different sorting functions
if (isset($_POST["date"])) {
    usort($runs, "date_sort");
    $_POST = array();
}
if (isset($_POST["distance"])) {
    usort($runs, "distance_sort");
    $_POST = array();
}
if (isset($_POST["time"])) {
    usort($runs, "time_sort");
    $_POST = array();
}


?>

<?= header_template() ?>
<?= nav_template() ?>


<!-- TODO: Incorportate sorting functionality:  sort by distance, sort by pace? , sort by date, sort by time elapsed -->
<a href="index.php" class="text-center text-info p-2"> > Return to Home</a>
<div class="container py-3">
    <h2 class="text-center">Run List</h2>
    <div class="container text-center py-2">
        <form method="post">
            <button class="btn btn-primary" name="distance">Sort By Distance (high to low)</button>
            <button class="btn btn-primary" name="date">Sort By Date (latest to earliest)</button>
            <button class="btn btn-primary" name="time">Sort By Time (longest to shortest)</button>
        </form>
    </div>
    <div style="overflow-x:scroll;">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Distance</th>
                    <th scope="col">Pace</th>
                    <th scope="col">Time Elapsed</th>
                    <th scope="col">Date</th>
                    <th scope="col"></th>
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
                            <?= $run['description'] ?>
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
                        <td>
                            <a href="delete-run.php?id=<?= $run['id'] ?>" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>


<?= footer_template() ?>