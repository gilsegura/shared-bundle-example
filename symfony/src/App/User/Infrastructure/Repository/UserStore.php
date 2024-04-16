<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use Shared\Domain\Uuid;
use Shared\EventHandling\EventBusInterface;
use Shared\EventSourcing\AbstractEventSourcingRepository;
use Shared\EventSourcing\EventStreamDecoratorInterface;
use Shared\Upcasting\SequentialUpcasterChain;
use Shared\Upcasting\UpcastingEventStore;
use SharedBundle\EventStore\DBALEventStore;

final readonly class UserStore extends AbstractEventSourcingRepository implements UserRepositoryInterface
{
    public function __construct(
        DBALEventStore $eventStore,
        EventBusInterface $eventBus,
        EventStreamDecoratorInterface $streamDecorator,
        UserFactory $userFactory
    ) {
        parent::__construct(
            new UpcastingEventStore($eventStore, new SequentialUpcasterChain()),
            $eventBus,
            $streamDecorator,
            $userFactory
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
