<?php
$hostname="localhost";
$dbuser="root";
$dbpassword="";
$dbname="food_order";
$conn=mysqli_connect($hostname,$dbuser,$dbpassword,$dbname);
if(!$conn){
    die("someting went wrong");
}
?>