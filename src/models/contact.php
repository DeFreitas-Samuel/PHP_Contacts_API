<?php

declare(strict_types=1);

namespace Samueldefreitas\PhpContactsApi\models;


class Contact
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public array $contactNumbers;
}
