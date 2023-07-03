<?php

declare(strict_types=1);

namespace Samueldefreitas\PhpContactsApi\database;

use Samueldefreitas\PhpContactsApi\Config;
use Samueldefreitas\PhpContactsApi\models\Contact;



class Database
{


    /**
     * @return array An array that contains all the users and their phone contacts
     */

    public static function getAllContactsFromDB(): array
    {
        $db = Config::getDB();

        $searchAllContactsquery = "SELECT *
        FROM contacts
        INNER JOIN contact_numbers
        ON contacts.id = contact_numbers.contact_id;";

        $allContactsFound = [];

        $allUsers = $db->query($searchAllContactsquery);
        while ($row = $allUsers->fetch(\PDO::FETCH_ASSOC)) {
            array_push($allContactsFound, $row);
        }

        return $allContactsFound;
    }

    public static function getOneContactFromDB(int $id): array
    {
        $db = Config::getDB();
        $queryThatSearchForOneUser = "SELECT *
        FROM contacts
        INNER JOIN contact_numbers
        ON contacts.id = contact_numbers.contact_id
        WHERE contacts.id = :id;";

        $preparedStatement = $db->prepare($queryThatSearchForOneUser);
        $preparedStatement->execute(["id" => $id]);
        $wantedUser = $preparedStatement->fetchAll(\PDO::FETCH_ASSOC);
        return $wantedUser;
    }

    /**
     * @return bool A bool value that indicates if the value was created correctly
     */

    public static function insertOneContactFromDB(Contact $contact): bool
    {
        $db = Config::getDB();

        try {
            $db->beginTransaction();
            $insertContactQuery = $db->prepare("INSERT INTO contacts VALUES(NULL, :firstname, :lastname, :email)");
            $insertContactQuery->execute([
                "firstname" => $contact->firstName,
                "lastname" => $contact->lastName,
                "email" => $contact->email
            ]);



            $contactInsertedId = $db->lastInsertId();

            foreach ($contact->contactNumbers as $contactNumber) {
                $insertContactNumberQuery = $db->prepare("INSERT INTO contact_numbers VALUES(NULL, :contactId, :contactNumber)");
                $insertContactNumberQuery->execute([
                    "contactId" => $contactInsertedId,
                    "contactNumber" => $contactNumber
                ]);
            }

            $db->commit();
            return true;
        } catch (\PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    /**
     * @return bool A bool value that indicates if the value was updated correctly
     */
    public static function updateOneContactFromDB(int $id, Contact $contactToBeUploaded): bool
    {
        $db = Config::getDB();

        try {
            $db->beginTransaction();
            $insertContactQuery = $db->prepare("UPDATE contacts 
                                                SET firstname = :firstname, lastname = :lastname, email = :email
                                                WHERE id = :id ");
            $insertContactQuery->execute([
                "firstname" => $contactToBeUploaded->firstName,
                "lastname" => $contactToBeUploaded->lastName,
                "email" => $contactToBeUploaded->email,
                "id" => $id
            ]);

            $db->prepare("DELETE FROM contact_numbers WHERE contact_id = :contactId")->execute(["contactId" => $id]);
            foreach ($contactToBeUploaded->contactNumbers as $contactNumber) {
                $insertContactNumberQuery = $db->prepare("INSERT INTO contact_numbers VALUES(NULL, :contactId, :contactNumber)");
                $insertContactNumberQuery->execute([
                    "contactId" => $id,
                    "contactNumber" => $contactNumber
                ]);
            }

            $db->commit();
            return true;
        } catch (\PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    /**
     * @return bool A bool value that indicates if the value was deleted correctly
     */
    public static function deleteOneContactFromDB(int $id): bool
    {
        $db = Config::getDB();

        try {
            $db->beginTransaction();

            $db->prepare("DELETE FROM contact_numbers WHERE contact_id = :contactId")->execute(["contactId" => $id]);

            $db->prepare("DELETE FROM contacts WHERE id = :contactId")->execute(["contactId" => $id]);

            $db->commit();
            return true;
        } catch (\PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
}
