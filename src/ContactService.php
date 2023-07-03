<?php

declare(strict_types=1);

namespace Samueldefreitas\PhpContactsApi;

use Samueldefreitas\PhpContactsApi\database\Database;



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
}
