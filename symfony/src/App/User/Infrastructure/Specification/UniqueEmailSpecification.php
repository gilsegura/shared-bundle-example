<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Specification;

use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Infrastructure\ReadModel\Repository\UserReadModelRepository;
use Shared\Criteria;
use Shared\Domain\Email;
use Shared\Specification\AbstractSpecification;

final readonly class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    public function __construct(
        private UserReadModelRepository $userRepository,
    ) {
    }

    #[\Override]
    public function __invoke(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    /**
     * @param Email ...$params
     */
    #[\Override]
    protected function isSatisfiedBy(mixed ...$params): bool
    {
        [$email] = $params;

        if (0 !== $this->userRepository->total(new Criteria\AndX(new Criteria\EqEmail($email)))) {
            throw UserAlreadyExistsException::email($email);
        }

        return true;
    }
}
