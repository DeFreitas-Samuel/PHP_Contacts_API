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


    /**
     * @return void 
     */

    public static function insertOneContact(Contact $contact): void
    {
        $db = Config::getDB();

        try {
            $db->beginTransaction();
            $insertContactQuery = $db->prepare('INSERT INTO contacts VALUES(NULL, :firstname, :lastname, :email)');
            $insertContactQuery->execute([
                "firstname" => $contact->firstName,
                "lastname" => $contact->lastName,
                "email" => $contact->email
            ]);



            $contactInsertedId = $db->lastInsertId();

            foreach ($contact->contactNumbers as $contactNumber) {
                $insertContactNumberQuery = $db->prepare('INSERT INTO contact_numbers VALUES(NULL, :contactId, :contactNumber)');
                $insertContactNumberQuery->execute([
                    "contactId" => $contactInsertedId,
                    "contactNumber" => $contactNumber
                ]);
            }

            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function deleteOneContact(int $id): void
    {
        $db = Config::getDB();

        try {
            $db->beginTransaction();

            $db->prepare("DELETE FROM contact_numbers WHERE contact_id = :contactId")->execute(["contactId" => $id]);

            $db->prepare("DELETE FROM contacts WHERE id = :contactId")->execute(["contactId" => $id]);

            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
}
