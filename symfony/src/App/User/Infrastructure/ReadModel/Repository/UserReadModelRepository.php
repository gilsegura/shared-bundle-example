<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ReadModel\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use App\User\Domain\ReadModel\UserView;
use Doctrine\ORM\EntityManagerInterface;
use Shared\Criteria;
use Shared\Domain\Uuid;
use Shared\ReadModel\ReadModelRepositoryException;
use SharedBundle\Persistence\Doctrine\AbstractObjectManager;

final readonly class UserReadModelRepository extends AbstractObjectManager implements UserReadModelRepositoryInterface
{
    public function __construct(
        EntityManagerInterface $manager,
    ) {
        parent::__construct($manager, $manager->getRepository(UserView::class));
    }

    #[\Override]
    public function oneOrException(Uuid $id): UserView
    {
        $users = $this->findBy(new Criteria\AndX(new Criteria\EqId($id)), null, 0, 1);

        if ([] === $users) {
            throw UserNotFoundException::new($id);
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
        return $this->search($criteria, $sort, $offset, $limit);
    }

    #[\Override]
    public function total(Criteria\AndX|Criteria\OrX|null $criteria = null): int
    {
        return $this->count($criteria);
    }

    #[\Override]
    public function save(UserView $user): void
    {
        try {
            $this->register($user);
        } catch (\Throwable $throwable) {
            throw ReadModelRepositoryException::new($throwable);
        }
    }
}
