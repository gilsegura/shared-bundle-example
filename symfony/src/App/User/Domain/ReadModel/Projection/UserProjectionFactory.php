<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel\Projection;

use App\User\Domain\Event\UserEmailWasChanged;
use App\User\Domain\Event\UserPasswordWasChanged;
use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use App\User\Domain\ReadModel\UserView;
use Shared\ReadModel\AbstractProjector;

final readonly class UserProjectionFactory extends AbstractProjector
{
    public function __construct(
        private UserReadModelRepositoryInterface $userRepository,
    ) {
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $user = UserView::fromSerializable($event);

        $this->userRepository->save($user);
    }

    protected function applyUserEmailWasChanged(UserEmailWasChanged $event): void
    {
        $user = $this->userRepository->oneOrException($event->id);

        $user->changeEmail($event->email);
        $user->changeUpdatedAt($event->updatedAt);

        $this->userRepository->save($user);
    }

    protected function applyUserPasswordWasChanged(UserPasswordWasChanged $event): void
    {
        $user = $this->userRepository->oneOrException($event->id);

        $user->changePassword($event->password);
        $user->changeUpdatedAt($event->updatedAt);

        $this->userRepository->save($user);
    }
}
