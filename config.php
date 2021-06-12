<?php

//DB Connection Info
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'W01260213';
$DATABASE_PASS = 'Koltencs!';
$DATABASE_NAME = 'W01260213';

//Try and connect using the info above: 
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
  //If there is an error with the connection, stop the script and display the error. 
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

function pdo_connect_mysql()
{
  //db connection constants
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'W01260213';
  $DATABASE_PASS = 'Koltencs!';
  $DATABASE_NAME = 'W01260213';

  //db connection
  try {
    return  new PDO(
      'mysql:host=' . $DATABASE_HOST . ';dbname=' .
        $DATABASE_NAME . ';charset=utf8',
      $DATABASE_USER,
      $DATABASE_PASS
    );
  } catch (PDOException $exception) {
    die('Failed to connect to the database.');
  }
}




function header_template($title  = "Run On")
{

  echo <<<EOT
    <!DOCTYPE html>
    <html>
  
      <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>$title</title>
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"/>
       <link rel="preconnect" href="https://fonts.gstatic.com">
       <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
       <link rel="stylesheet" type="text/css" href="./main.css" />
      </head>
  
    <body>

    EOT;
}

function nav_template()
{
  echo <<<EOT
        <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand text-info" href="index.php">Run On</a>
        <div class="" id="navbarNav">
            <ul class="navbar-nav">
      EOT;
  if (!isset($_SESSION['loggedin'])) {
    echo
    '<li class="nav-item active">
            <a class="nav-link text-info" href="login.php">Login <span class="sr-only">(current)</span></a>
            </li>';
  }
  if (isset($_SESSION['loggedin'])) {
    echo
    '<li class="nav-item active">
            <a class="nav-link text-info" href="logout.php">Logout <span class="sr-only">(current)</span></a>
            </li>';
  }
  echo <<<EOT
          
          </ul>
        </div>
    </nav>
    EOT;
}

function footer_template()
{

  echo <<<EOT
        <!-- START FOOTER -->
        <footer class="text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3">
            © 2021 Fake Copyright:
            <a class="" href="#">Run On</a>
        </div>
        <!-- Copyright -->
        </footer>
        <!-- END FOOTER -->
        </body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/db631b8d47.js" crossorigin="anonymous"></script>
        </html>
    EOT;
}


function password_protect()
{
  session_start();

  //if not logged in, redirect to login page 
  if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
  }
}

//Feedback object
class UserFeedback
{
  //Properties 
  public $msg;
  public $route;
  public $color;

  //Setter
  public function set_feedback($msg, $route, $color)
  {
    $this->msg = $msg;
    $this->route = $route;
    $this->color = $color;
  }

  //Template for displaying feedback message
  public function template_feedback()
  {
    echo <<<EOT
    <div class="alert alert-$this->color">
      $this->msg Return to <a class="text-info" href="$this->route.php">$this->route</a> page, otherwise you will be redirected in 3 seconds.
    </div>
    EOT;
    header("Refresh:3; url=" . $this->route . ".php");
  }
}

//Instantiating global feedback object
$user_feedback = new UserFeedback();

function date_sort($a, $b) {
  return strtotime($b['date']) - strtotime($a['date']);
}

function distance_sort($a, $b) {
  return (int)$b['distance'] - (int)$a['distance'];
}

function time_sort($a, $b) {
  return (int)$b['time_elapsed'] - (int)$a['time_elapsed'];
}