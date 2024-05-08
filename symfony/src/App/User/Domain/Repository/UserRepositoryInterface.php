<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Exception\UserEmailAlreadyExistsException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\User;
use Shared\Domain\Uuid;
use Shared\EventHandling\EventBusException;
use Shared\EventStore\EventStoreException;

interface UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function get(Uuid $id, ?int $playhead = null): User;

    /**
     * @throws UserEmailAlreadyExistsException
     * @throws EventStoreException
     * @throws EventBusException
     */
    public function store(User $user): void;
}
