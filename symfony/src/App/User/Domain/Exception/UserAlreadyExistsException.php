<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Shared\Domain\DomainException;
use Shared\Domain\Email;

final class UserAlreadyExistsException extends DomainException
{
    public static function email(Email $email): self
    {
        return new self(sprintf('The user user email "%s" already exists.', $email->email));
    }
}
