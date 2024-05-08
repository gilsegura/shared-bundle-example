<?php

declare(strict_types=1);

namespace App\User\Application\Command\ChangeUserPassword;

use App\User\Domain\Repository\UserRepositoryInterface;
use Shared\CommandHandling\CommandHandlerInterface;

final readonly class ChangeUserPasswordHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $user = $this->userRepository->get($command->id);

        $user->changePassword(
            $command->oldPlainPassword,
            $command->password
        );

        $this->userRepository->store($user);
    }
}
