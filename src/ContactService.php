<?php

declare(strict_types=1);

namespace Samueldefreitas\PhpContactsApi;

use Samueldefreitas\PhpContactsApi\database\Database;
use Samueldefreitas\PhpContactsApi\models\Contact;



class ContactService
{
    public static function GetAllContacts()
    {
        return Database::getAllContactsFromDB();
    }
    public static function GetOneContact(int $id)
    {
        return Database::getOneContactFromDB($id);
    }
    public static function AddOneContact(Contact $contact)
    {
        return Database::insertOneContactFromDB($contact);
    }
    public static function DeleteAUser(int $id)
    {
        return Database::deleteOneContactFromDB($id);
    }
}
