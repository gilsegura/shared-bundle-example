<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use Shared\Domain\Uuid;
use Shared\EventHandling\EventBusInterface;
use Shared\EventSourcing\AbstractEventSourcingRepository;
use Shared\EventSourcing\EventStreamDecoratorInterface;
use Shared\EventSourcing\Factory\PublicConstructorAggregateRootFactory;
use Shared\Upcasting\SequentialUpcasterChain;
use Shared\Upcasting\UpcastingEventStore;
use SharedBundle\EventStore\DoctrineEventStore;

final readonly class UserStore extends AbstractEventSourcingRepository implements UserRepositoryInterface
{
    public function __construct(
        DoctrineEventStore $eventStore,
        EventBusInterface $eventBus,
        EventStreamDecoratorInterface $streamDecorator,
    ) {
        parent::__construct(
            new UpcastingEventStore($eventStore, new SequentialUpcasterChain()),
            $eventBus,
            $streamDecorator,
            new PublicConstructorAggregateRootFactory(User::class)
        );
    }

    #[\Override]
    public function get(Uuid $id, ?int $playhead = null): User
    {
        return $this->load($id, $playhead);
    }

    #[\Override]
    public function store(User $user): void
    {
        $this->save($user);
    }
}
