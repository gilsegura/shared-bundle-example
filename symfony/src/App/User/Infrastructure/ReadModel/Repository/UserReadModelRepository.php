<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ReadModel\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use App\User\Domain\ReadModel\User;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Criteria;
use Shared\Domain\Uuid;
use Shared\ReadModel\ReadModelRepositoryException;
use SharedBundle\Persistence\Doctrine\AbstractObjectManager;

/**
 * @template-extends AbstractObjectManager<int, User>
 */
final readonly class UserReadModelRepository extends AbstractObjectManager implements UserReadModelRepositoryInterface
{
    public function __construct(
        EntityManagerInterface $manager,
    ) {
        parent::__construct($manager, $manager->getRepository(User::class));
    }

    #[\Override]
    public function oneOrException(Uuid $id): User
    {
        $users = $this->findBy(new Criteria\AndX(new Criteria\EqId($id)), null, 0, 1);

        if ([] === $users) {
            throw UserNotFoundException::id($id);
        }

        return $users[0];
    }

    #[\Override]
    public function findBy(
        Criteria\AndX|Criteria\OrX|null $criteria = null,
        ?Criteria\OrderX $sort = null,
        ?int $offset = null,
        ?int $limit = null,
    ): array {
        try {
            return $this->search($criteria, $sort, $offset, $limit);
        } catch (\Throwable $e) {
            throw ReadModelRepositoryException::throwable($e);
        }
    }

    public function total(Criteria\AndX|Criteria\OrX|null $criteria = null): int
    {
        try {
            return $this->count($criteria);
        } catch (\Throwable $e) {
            throw ReadModelRepositoryException::throwable($e);
        }
    }

    #[\Override]
    public function save(User $user): void
    {
        try {
            $this->register($user);
        } catch (\Throwable $e) {
            throw ReadModelRepositoryException::throwable($e);
        }
    }
}
