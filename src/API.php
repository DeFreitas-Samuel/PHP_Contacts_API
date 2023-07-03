<?php

declare(strict_types=1);

namespace Samueldefreitas\PhpContactsApi;




class API
{
    public static function listen()
    {
        $requestUrl = $_SERVER["REQUEST_URI"];
        $httpMethod = $_SERVER["REQUEST_METHOD"];


        if ($requestUrl == "/contacts" && $httpMethod == "GET") {
            //header('Content-Type: application/json');

            $allContacts =  ContactService::getAllContacts();
            var_dump($allContacts);
        } elseif (preg_match("/contact\/\d+/", $requestUrl) && $httpMethod == "GET") {
            self::showOneContact($requestUrl);
        } elseif ($requestUrl == "/contact" && $httpMethod == "POST") {
            header('Content-Type: application/json');
            http_response_code(201);

            echo "Eminencia";
        } elseif (preg_match("/contact\/\d+/", $requestUrl) && $httpMethod == "PUT") {
            http_response_code(204);

            echo "Moscow Mule";
        } elseif (preg_match("/contact\/\d+/", $requestUrl) && $httpMethod == "DELETE") {
            http_response_code(204);


            echo "Moscow Mule";
        } else {
            http_response_code(404);
        }
    }

    private static function showOneContact(string $requestUrl)
    {
        header('Content-Type: application/json');
        preg_match("/\d+/", $requestUrl, $matches);
        $userId = (int)$matches[0];

        $requestedContact = ContactService::GetOneContact($userId);

        $id = $requestedContact[0]['contact_id'];
        $firstname = $requestedContact[0]['firstname'];
        $lastname = $requestedContact[0]['lastname'];
        $email = $requestedContact[0]['email'];
        $numbers = array();
        foreach ($requestedContact as $row) {
            $numbers[] = $row['phone_number'];
        }

        $data = [
            "id" => $id,
            "fistname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "contactNumbers" => $numbers
        ];

        echo json_encode($data);
    }
}
