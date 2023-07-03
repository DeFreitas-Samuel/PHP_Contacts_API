<?php


class Database
{


    /**
     * @return array An array that contains all the users and their phone contacts
     */

    public static function getAllContacts(): array
    {
        $db = Config::getDB();

        $searchAllContactsquery = "SELECT *
        FROM contacts
        INNER JOIN contact_numbers
        ON contacts.id = contact_numbers.contact_id;";

        $allContactsFound = [];

        $allUsers = $db->query($searchAllContactsquery);
        while ($row = $allUsers->fetch(PDO::FETCH_OBJ)) {
            array_push($allContactsFound, $row);
        }


        return $allContactsFound;
    }

    public static function insertOneContact(Contact $contact): bool
    {
        $db = Config::getDB();


        $insertContactQuery = $db->prepare('INSERT INTO contacts VALUES(NULL, :firstname, :lastname, :email)');
        $resultOfInsertingContact = $insertContactQuery->execute(["firstname" => $contact->firstName, "lastname" => $contact->lastName, "email" => $contact->email]);


        if ($resultOfInsertingContact) {

            $userInsertedId = $db->lastInsertId();

            $result = false;
            foreach ($contact->contactNumbers as $contactNumber) {
                var_dump($userInsertedId);
                var_dump($contact->contactNumbers);
                var_dump($contactNumber);
                $insertContactNumberQuery = $db->prepare('INSERT INTO contact_numbers VALUES(NULL, :contactId, :contactNumber)');
                $result = $insertContactNumberQuery->execute(["contactId" => $userInsertedId, "contactNumber" => $contactNumber]);
            }
            return $result;
        }



        return false;
    }
}
