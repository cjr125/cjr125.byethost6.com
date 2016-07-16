<html>
<head>
</head>
<body>
<?php
$db_host = 'mysql13.000webhost.com';
$db_user = 'a8823305_cjr125';
$db_pwd = '4Cropo.L15';

$database = 'a8823305_audio';
$table = 'cjrmusic';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

/*$sql = "INSERT INTO {$table} (title, price, duration, location)
VALUES ('wise', '0', '02:16:00', 'http://www.cjr125.netai.net/audio/wise.mp3')";

if (mysql_query($sql) === TRUE) {
    echo "New record created successfully<br>";
} else {
    echo "Error!<br>";
}*/

/*$sql = "DELETE FROM {$table} WHERE title LIKE '%contention%'";

if (mysql_query($sql) === TRUE) {
    echo "record deleted successfully<br>";
} else {
    echo "Error!<br>";
}*/

// sending query
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<table border='1'><tr><td>row #</td>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
$row_num = 1;
while($row = mysql_fetch_row($result))
{
    echo "<tr><td>".$row_num."</td>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

    echo "</tr>\n";
    $row_num++;
}
mysql_free_result($result);
?>
</body>
</html>		