<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetUser;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\ReadModel\Repository\UserReadModelRepositoryInterface;
use Shared\CommandHandling\Item;
use Shared\CommandHandling\QueryHandlerInterface;
use SharedBundle\Criteria\CriteriaConverterException;

final readonly class GetUserHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserReadModelRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws CriteriaConverterException
     */
    public function __invoke(GetUserQuery $query): Item
    {
        $user = $this->userRepository->oneOrException($query->id);

        return Item::fromSerializable($user);
    }
}
