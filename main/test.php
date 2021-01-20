<?php

$d = date("2021-01-18T13:13:13.745Z");
$d = str_replace("CET", "-", $d);
$d = substr($d, 0, -8);

$d = str_replace(":", "-", $d);
$d = explode("-", $d);
print(mktime($d[5],$d[4],$d[3],$d[1],$d[2],$d[0]));
print("<br>");
print(mktime(13,13,13, 01,18,2021));
?>
