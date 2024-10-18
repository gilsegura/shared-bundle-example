<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Shared\Domain\Uuid;

final class AuthenticationUserException extends \Exception
{
    public static function new(Uuid $id): self
    {
        return new self(sprintf('User with id "%s" is not authorized.', $id->uuid));
    }
}
