<?php


mysql_connect('localhost', 'zachary_wpab', 'ABDOMINALS');
mysql_select_db('zachary_wpab');
$query = sprintf("INSERT INTO clicks (treatment) VALUES ('%s')", mysql_real_escape_string($_POST['treatment']));
mysql_query($query);
return;

?>