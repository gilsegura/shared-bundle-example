<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Shared\Domain\Email;
use Shared\Exception\AlreadyExistsException;

final class UserEmailAlreadyExistsException extends AlreadyExistsException
{
    public static function new(Email $email): self
    {
        return new self(sprintf('User with email "%s" already exists.', $email->email));
    }
}
