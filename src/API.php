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
            header('Content-Type: application/json');


            echo self::encodeAllUsers();
        } elseif (preg_match("/contact\/\d+/", $requestUrl) && $httpMethod == "GET") {

            header('Content-Type: application/json');
            preg_match("/\d+/", $requestUrl, $matches);
            $userId = (int)$matches[0];

            echo self::encodeOneContact($userId);
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

    private static function encodeOneContact(int $userId)
    {
        $requestedContact = ContactService::GetOneContact($userId);

        $id = $requestedContact[0]['contact_id'];
        $firstname = $requestedContact[0]['firstname'];
        $lastname = $requestedContact[0]['lastname'];
        $email = $requestedContact[0]['email'];
        $numbers = array();
        foreach ($requestedContact as $entry) {
            $numbers[] = $entry['phone_number'];
        }

        $requestedContactJSON = [
            "id" => $id,
            "fistname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "contactNumbers" => $numbers
        ];

        return json_encode($requestedContactJSON);
    }

    private static function encodeAllUsers()
    {
        $allContacts =  ContactService::getAllContacts();

        $contactsToReturn = [];

        foreach ($allContacts as $entry) {
            $contactId = $entry['contact_id'];
            $contactIndex = -1;

            foreach ($contactsToReturn as $index => $contact) {
                if ($contact['id'] === $contactId) {
                    $contactIndex = $index;
                    break;
                }
            }

            if ($contactIndex === -1) {
                $newContact = [
                    'id' => $entry['contact_id'],
                    'firstname' => $entry['firstname'],
                    'lastname' => $entry['lastname'],
                    'email' => $entry['email'],
                    'phoneNumbers' => [$entry['phone_number']]
                ];
                $contactsToReturn[] = $newContact;
            } else {

                $contactsToReturn[$contactIndex]['phoneNumbers'][] = $entry['phone_number'];
            }
        }
        return json_encode($contactsToReturn);
    }
}
