<?php
// require 'config.php';
// require "database/database.php";
// require "src/models/contact.php";

// $db = Config::getDB();

// $info = $db->query("SELECT * FROM `contacts`");
// while ($row = $info->fetch()) {
//     echo $row['firstname'] . "\n";
// }
// echo "Hello World";

// Database::getAllContacts();
// $newContact = new Contact();
// $newContact->firstName = "Billie";
// $newContact->lastName = "Eilish";
// $newContact->email = "billie@gmail.com";
// $newContact->contactNumbers = [1231231232, 1231234567, 4567811111];
// Database::insertOneContact($newContact);
// Database::deleteOneContact(8);
// var_dump(Database::getOneContact(1));

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_uri == '/contacts' && $request_method == 'GET') {
    echo "Hello";
}
