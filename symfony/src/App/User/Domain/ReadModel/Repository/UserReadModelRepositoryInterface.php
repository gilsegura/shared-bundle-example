<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\UserView;
use Shared\Criteria;
use Shared\Domain\Uuid;
use Shared\ReadModel\ReadModelRepositoryException;

interface UserReadModelRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function oneOrException(Uuid $id): UserView;

    /**
     * @return UserView[]
     */
    public function findBy(
        Criteria\AndX|Criteria\OrX|null $criteria = null,
        ?Criteria\OrderX $sort = null,
        ?int $offset = null,
        ?int $limit = null,
    ): array;

    public function total(Criteria\AndX|Criteria\OrX|null $criteria = null): int;

    /**
     * @throws ReadModelRepositoryException
     */
    public function save(UserView $user): void;
}
