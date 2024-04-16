<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\User;
use Shared\Domain\Uuid;
use Shared\EventHandling\EventBusException;
use Shared\EventStore\DomainEventStreamNotFoundException;
use Shared\EventStore\EventStoreException;
use Shared\EventStore\PlayheadAlreadyExistsException;

interface UserRepositoryInterface
{
    /**
     * @throws DomainEventStreamNotFoundException
     */
    public function get(Uuid $id, ?int $playhead = null): User;

    /**
     * @throws PlayheadAlreadyExistsException
     * @throws EventStoreException
     * @throws EventBusException
     */
    public function store(User $user): void;
}
