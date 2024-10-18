<?php

declare(strict_types=1);

namespace App\User\Domain\Specification;

use App\User\Domain\Exception\UserEmailAlreadyExistsException;
use Shared\Domain\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws UserEmailAlreadyExistsException
     */
    public function isUnique(Email $email): bool;
}
