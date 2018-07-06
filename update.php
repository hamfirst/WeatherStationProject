<?php

$post_data = stream_get_contents(STDIN);
$samples = json_decode($post_data);

if(!is_array($samples))
{
    die("Invalid json data");
}

require('databaseinfo.php');
$db = mysqli_connect('127.0.0.1', $db_user, $db_pass, $db_name);

if(!$db)
{
    die('Could not connect: ' . mysqli_error());
}

$statement = $db->prepare("INSERT INTO samples (time, air_temp, ground_temp, pressure, humidity, air_conductivity, light) VALUES (?,?,?,?,?,?,?)");

foreach($sample as &$samples)
{
    $statement->bind_param("sdddddd", 
                            $sample["time"], 
                            $sample["air_temp"], 
                            $sample["ground_temp"], 
                            $sample["pressure"],
                            $sample["air_conductivity"],
                            $sample["light"]);
    $statement->execute();
}

echo "Ok";
?>