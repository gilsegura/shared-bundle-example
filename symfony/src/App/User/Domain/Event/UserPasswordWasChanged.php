<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use Assert\Assertion;
use Shared\Domain\DateTimeImmutable;
use Shared\Domain\DomainEventInterface;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;

final readonly class UserPasswordWasChanged implements DomainEventInterface
{
    public function __construct(
        public Uuid $id,
        public HashedPassword $password,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    #[\Override]
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'password');
        Assertion::keyExists($data, 'updated_at');

        return new self(
            new Uuid($data['id']),
            new HashedPassword($data['password']),
            new DateTimeImmutable($data['updated_at'])
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return [
            'id' => $this->id->uuid,
            'password' => $this->password->password,
            'updated_at' => $this->updatedAt->dateTime,
        ];
    }
}
