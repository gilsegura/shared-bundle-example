<?php

declare(strict_types=1);

namespace App\User\Application\Command\CreateUser;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use App\User\Infrastructure\Specification\UniqueEmailSpecification;
use Shared\CommandHandling\CommandHandlerInterface;

final readonly class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UniqueEmailSpecification $uniqueEmailSpecification,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = User::create(
            $command->id,
            $command->email,
            $command->password,
            $this->uniqueEmailSpecification
        );

        $this->userRepository->store($user);
    }
}
