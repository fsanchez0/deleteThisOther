<?php

$enlace = mysql_connect('localhost', 'rentaspb_root', 'UOMduFs4&N*.');
mysql_select_db('rentaspb_adminren',$enlace) ;
$connection = mysqli_connect('localhost', 'rentaspb_root', 'UOMduFs4&N*.', 'rentaspb_adminren');

mysql_query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
mysql_query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES',''))");

?>