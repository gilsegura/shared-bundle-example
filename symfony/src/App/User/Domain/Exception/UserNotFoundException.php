<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Shared\Domain\Uuid;
use Shared\Exception\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    public static function new(Uuid $id): self
    {
        return new self(sprintf('User with id "%s" not found.', $id->uuid));
    }
}
