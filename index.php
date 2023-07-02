<?php
require 'config.php';


$db = Config::getDB();

$info = $db->query("SELECT * FROM `contacts`");
var_dump($info);
echo "Hello World";
