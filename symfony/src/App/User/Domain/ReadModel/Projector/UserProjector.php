<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel\Projector;

use App\User\Domain\Event\UserEmailWasChanged;
use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use App\User\Domain\ReadModel\User;
use Shared\ReadModel\AbstractProjector;
use Shared\ReadModel\ReadModelRepositoryException;

final readonly class UserProjector extends AbstractProjector
{
    public function __construct(
        private UserReadModelRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws ReadModelRepositoryException
     */
    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $user = User::fromSerializable($event);

        $this->userRepository->save($user);
    }

    /**
     * @throws UserNotFoundException
     * @throws ReadModelRepositoryException
     */
    protected function applyUserEmailWasChanged(UserEmailWasChanged $event): void
    {
        $user = $this->userRepository->oneOrException($event->id);

        $user->changeEmail($event->email);
        $user->changeUpdatedAt($event->updatedAt);

        $this->userRepository->save($user);
    }
}
