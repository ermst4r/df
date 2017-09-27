<?php
header('Content-Type: application/json');
$link = mysqli_connect("localhost", "root", "root", "apimock");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}

//echo "Success: A proper connection to MySQL was made! The db database is great." . PHP_EOL;
//echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

$result = [];

/* check connection */
if ($link->connect_errno) {
    printf("Connect failed: %s\n", $link->connect_error);
    exit();
}

$countries = $link->query("select * from countries", MYSQLI_USE_RESULT);

while ($row = $countries->fetch_assoc()) {
    $row['id'] = (int) $row['id'];
    $result['country'][] = $row;
//    echo json_encode($row, true);
}

$countries->close();
//added countries

$channels = $link->query("select * from channel", MYSQLI_USE_RESULT);

while ($row = $channels->fetch_assoc()) {
    // types
    $row['id'] = (int) $row['id'];
    $row['country_id'] = (int) $row['country_id'];

    $result['channel'][] = $row;


}
$channels->close();
// added channels;

foreach($result['channel'] as &$c)
{

    $query = $link->query("select * from types where channel_id = ".$c['id'], MYSQLI_USE_RESULT);

    while ($row = $query->fetch_assoc()) {
        $row['id'] = (int) $row['id'];
        $row['fk_channel_id'] = (int) $row['channel_id'];
        unset($row['channel_id']);
        $c['channel_types'][] = $row;
    }
    // mappings


    $query->close();
}

foreach($result['channel'] as &$c)
{

    $query = $link->query("select id,field_name,description,field_type,type_id,parent,attributes from mapping where channel_id = ".$c['id'], MYSQLI_USE_RESULT);

    while ($row = $query->fetch_assoc()) {
        $row['id'] = (int) $row['id'];
        $row['field_type'] = (int) $row['field_type'];
        $row['type_id'] = (int) $row['type_id'];
        $row['attributes'] = json_decode($row['attributes']);

        $c['channel_mapping'][] = $row;
    }
    // mappings


    $query->close();
}


mysqli_close($link);
echo json_encode($result);

//var_dump($result);