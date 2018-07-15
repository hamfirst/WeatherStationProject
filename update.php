<?php

$post_data = file_get_contents("php://input");
$samples = json_decode($post_data);

if(!is_array($samples) || count($samples) == 0)
{
    die("wtf Invalid json data " . $post_data);
}

require('databaseinfo.php');
$db = mysqli_connect('127.0.0.1', $db_user, $db_pass, $db_name);

if(!$db)
{
    die('Could not connect: ' . mysqli_error());
}

$statement = $db->prepare("INSERT INTO samples (time, air_temp, ground_temp, pressure, humidity, air_conductivity, light) VALUES (?,?,?,?,?,?,?)");

foreach($samples as $sample)
{
    $time = mysqli_real_escape_string($db, $sample->time);
    $air_temp = mysqli_real_escape_string($db, $sample->air_temp);
    $ground_temp = mysqli_real_escape_string($db, $sample->ground_temp);
    $pressure = mysqli_real_escape_string($db, $sample->pressure);
    $humidity = mysqli_real_escape_string($db, $sample->humidity);
    $air_cond = mysqli_real_escape_string($db, $sample->air_conductivity);
    $light = mysqli_real_escape_string($db, $sample->light);

    $statement->bind_param("sdddddd", 
                            $time, 
                            $air_temp, 
                            $ground_temp, 
                            $pressure,
                            $humidity,
                            $air_cond,
                            $light);

    if(!$statement->execute())
    {
      die("mysql insert failed");
    }
}

mysqli_commit($db);

echo "Ok";
?>