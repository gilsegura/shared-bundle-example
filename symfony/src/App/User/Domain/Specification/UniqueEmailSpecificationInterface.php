<?php

declare(strict_types=1);

namespace App\User\Domain\Specification;

use App\User\Domain\Exception\UserAlreadyExistsException;
use Shared\Domain\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws UserAlreadyExistsException
     */
    public function __invoke(Email $email): bool;
}
