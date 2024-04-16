<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\User\Domain\User;
use Shared\Domain\DomainEventStream;
use Shared\EventSourcing\AggregateRootFactoryInterface;
use Shared\EventSourcing\AggregateRootInterface;

final class UserFactory implements AggregateRootFactoryInterface
{
    #[\Override]
    public function create(DomainEventStream $stream): AggregateRootInterface
    {
        $user = new User();
        $user->initialize($stream);

        return $user;
    }
}
