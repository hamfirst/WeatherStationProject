<?php

require('databaseinfo.php');
$db = mysqli_connect('127.0.0.1', $db_user, $db_pass, $db_name);

if(!$db)
{
    die('Could not connect: ' . mysqli_error());
}

$result = mysqli_query($db, 'SELECT * FROM samples');

$num_rows = mysqli_num_rows($result);

$t = array();
$air_temp = array();
$ground_temp = array();
$pressure = array();
$humidity = array();
$air_conductivity = array();
$light = array();

if($num_rows > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
        array_push($t, $row["time"]);
        array_push($air_temp, $row["air_temp"]);
        array_push($ground_temp, $row["ground_temp"]);
        array_push($pressure, $row["pressure"]);
        array_push($humidity, $row["humidity"]);
        array_push($air_conductivity, $row["air_conductivity"]);
        array_push($light, $row["light"]);
    }
}

$final_out = array('t' => $t, 
                   'air_temp' => $air_temp, 
                   'ground_temp' => $ground_temp, 
                   'pressure' => $pressure, 
                   'humidity' => $humidity,
                   'air_conductivity' => $air_conductivity,
                   'light' => $light);

echo json_encode($final_out);
mysqli_close($db);

?>