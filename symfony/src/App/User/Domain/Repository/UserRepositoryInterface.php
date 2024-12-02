<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\User;
use Shared\Domain\Uuid;
use Shared\EventSourcing\EventSourcingRepositoryException;

interface UserRepositoryInterface
{
    /**
     * @throws EventSourcingRepositoryException
     * @throws UserNotFoundException
     */
    public function get(Uuid $id, ?int $playhead = null): User;

    /**
     * @throws EventSourcingRepositoryException
     */
    public function store(User $user): void;
}
