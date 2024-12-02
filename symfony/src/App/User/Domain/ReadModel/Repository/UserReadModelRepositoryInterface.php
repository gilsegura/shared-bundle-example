<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\User;
use Shared\Criteria;
use Shared\Domain\Uuid;
use Shared\ReadModel\ReadModelRepositoryException;

interface UserReadModelRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     * @throws ReadModelRepositoryException
     */
    public function oneOrException(Uuid $id): User;

    /**
     * @return User[]
     *
     * @throws ReadModelRepositoryException
     */
    public function findBy(
        Criteria\AndX|Criteria\OrX|null $criteria = null,
        ?Criteria\OrderX $sort = null,
        ?int $offset = null,
        ?int $limit = null,
    ): array;

    /**
     * @throws ReadModelRepositoryException
     */
    public function save(User $user): void;
}
