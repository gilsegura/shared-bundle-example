<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetUser;

use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use App\User\Domain\ReadModel\User;
use Shared\CommandHandling\QueryHandlerInterface;

final readonly class GetUserHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserReadModelRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(GetUserQuery $query): User
    {
        return $this->userRepository->oneOrException($query->id);
    }
}
