<?php

declare(strict_types=1);

namespace App\User\Application\Command\ChangeUserEmail;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Specification\UniqueEmailSpecification;
use Shared\CommandHandling\CommandHandlerInterface;

final readonly class ChangeUserEmailHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UniqueEmailSpecification $uniqueEmailSpecification,
    ) {
    }

    public function __invoke(ChangeUserEmailCommand $command): void
    {
        $user = $this->userRepository->get($command->id);

        $user->changeEmail(
            $command->email,
            $this->uniqueEmailSpecification
        );

        $this->userRepository->store($user);
    }
}
