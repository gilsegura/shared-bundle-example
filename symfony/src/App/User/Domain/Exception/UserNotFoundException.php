<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Shared\Domain\Uuid;
use Shared\Exception\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    public static function id(Uuid $id): self
    {
        return new self(sprintf('The requested user "%s" could not be found.', $id->uuid));
    }
}
