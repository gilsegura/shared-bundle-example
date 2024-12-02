<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\Event\UserEmailWasChanged;
use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use Shared\Domain\DateTimeImmutable;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use Shared\EventSourcing\AbstractEventSourcedAggregateRoot;

final class User extends AbstractEventSourcedAggregateRoot
{
    private Uuid $id;

    private Email $email;

    private HashedPassword $password;

    private DateTimeImmutable $createdAt;

    private ?DateTimeImmutable $updatedAt = null;

    public static function create(
        Uuid $id,
        Email $email,
        HashedPassword $password,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ): self {
        $uniqueEmailSpecification->__invoke($email);

        $user = new self();

        $user->apply(new UserWasCreated(
            $id,
            $email,
            $password,
            DateTimeImmutable::now()
        ));

        return $user;
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->id = $event->id;
        $this->email = $event->email;
        $this->password = $event->password;
        $this->createdAt = $event->createdAt;
    }

    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ): void {
        if ($this->email->equals($email)) {
            return;
        }

        $uniqueEmailSpecification->__invoke($email);

        $this->apply(new UserEmailWasChanged(
            $this->id,
            $email,
            DateTimeImmutable::now()
        ));
    }

    protected function applyUserEmailWasChanged(UserEmailWasChanged $event): void
    {
        $this->email = $event->email;
        $this->updatedAt = $event->updatedAt;
    }

    #[\Override]
    public function id(): Uuid
    {
        return $this->id;
    }
}
