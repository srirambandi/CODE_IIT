<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * Common functions used throughout Codejudge
 */
session_start();
   define('host', 'localhost:3306');
   define('user', 'root');
   define('password', 'hydra_1x');
   define('database', 'test');
   $host = host;
   $user = user;
   $password = password;
   $database = database;
   $db = mysqli_connect(host,user,password,database);
   // // die(DB_DATABASE);
   if (mysqli_connect_error()) {
     die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
   }
// checks if any user is logged in
function loggedin() {
  echo $_SESSION['username'];
  return isset($_SESSION['username']);
}

// connects to the database
function connectdb() {
  // include('dbinfo.php');
  // echo "$host";
   // $db = new mysqli($host,$user,$password,$database);
   // if (mysqli_connect_error()) {
   //   die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
   // }
   define('host', 'localhost:3306');
   define('user', 'root');
   define('password', 'hydra_1x');
   define('database', 'test');
   $host = host;
   $user = user;
   $password = password;
   $database = database;
   $db = mysqli_connect(host,user,password,database);
   // // die(DB_DATABASE);
   if (mysqli_connect_error()) {
     die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
   }
}

// generates a random alpha numeric sequence. Used to generate salt
function randomAlphaNum($length){
  $rangeMin = pow(36, $length-1);
  $rangeMax = pow(36, $length)-1;
  $base10Rand = mt_rand($rangeMin, $rangeMax);
  $newRand = base_convert($base10Rand, 10, 36);
  return $newRand;
}

// gets the name of the event
function getName(){
  connectdb();
  $query="SELECT name FROM prefs";
  $result = mysqli_query($db,$query);
  $row = mysqli_fetch_array($result);
  return $row['name'];
}

// converts text to an uniform only '\n' newline break
function treat($text) {
	$s1 = str_replace("\n\r", "\n", $text);
	return str_replace("\r", "", $s1);
}
?>
