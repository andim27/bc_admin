<?php
$db = require dirname(__FILE__) . "/../config/db.php";
$connect_str= stristr($db['dsn'], "h");
$connect_str=str_replace(";", " ",$connect_str);
$connect_str.=" user=".$db['username']." password=".$db["password"];

$p= pg_connect($connect_str);
$res = pg_query($p, "SELECT prefix as sh FROM crm_language_list");
$languages = array();
while ($row = pg_fetch_assoc($res)) {
    $languages[] = $row['sh'];
}
return implode('|', $languages);

?>