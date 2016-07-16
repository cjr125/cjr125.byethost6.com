<html>
<head>
</head>
<body>
<?php
$db_host = 'mysql13.000webhost.com';
$db_user = 'a8823305_cjr125';
$db_pwd = '4Cropo.L15';

$database = 'a8823305_audio';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

$sql = "SELECT m.*, AVG(r.rating) as rating FROM cjrmusic m LEFT OUTER JOIN ratings r ON r.id_post = m.title GROUP BY m.title ORDER BY rating DESC";//"UPDATE cjrmusic SET title = 'divisibility' WHERE title = 'dvisibility'";
$result = mysql_query($sql);
if (!$result) {
    die("Query to show fields from table failed: ".mysql_error());
}

$fields_num = mysql_num_fields($result);

echo "<h1>Results:</h1>";
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