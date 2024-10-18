<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\Event\UserEmailWasChanged;
use App\User\Domain\Event\UserPasswordWasChanged;
use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\Exception\AuthenticationUserException;
use App\User\Domain\Exception\UserEmailAlreadyExistsException;
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

    /**
     * @throws UserEmailAlreadyExistsException
     */
    public static function create(
        Uuid $id,
        Email $email,
        HashedPassword $password,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ): self {
        $uniqueEmailSpecification->isUnique($email);

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

    /**
     * @throws UserEmailAlreadyExistsException
     */
    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
    ): void {
        if ($this->email->equals($email)) {
            return;
        }

        $uniqueEmailSpecification->isUnique($email);

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

    /**
     * @throws AuthenticationUserException
     */
    public function changePassword(string $oldPlainPassword, HashedPassword $password): void
    {
        if (!$this->password->match($oldPlainPassword)) {
            throw AuthenticationUserException::new($this->id);
        }

        $this->apply(new UserPasswordWasChanged(
            $this->id,
            $password,
            DateTimeImmutable::now()
        ));
    }

    protected function applyUserPasswordWasChanged(UserPasswordWasChanged $event): void
    {
        $this->password = $event->password;
        $this->updatedAt = $event->updatedAt;
    }

    #[\Override]
    public function id(): Uuid
    {
        return $this->id;
    }
}
