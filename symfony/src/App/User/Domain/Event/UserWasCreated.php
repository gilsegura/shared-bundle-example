<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use ProxyAssert\Assertion;
use Shared\Domain\DateTimeImmutable;
use Shared\Domain\DomainEventInterface;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;

final readonly class UserWasCreated implements DomainEventInterface
{
    public function __construct(
        public Uuid $id,
        public Email $email,
        public HashedPassword $password,
        public DateTimeImmutable $createdAt,
    ) {
    }

    #[\Override]
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'email');
        Assertion::keyExists($data, 'password');
        Assertion::keyExists($data, 'created_at');

        return new self(
            new Uuid($data['id']),
            new Email($data['email']),
            new HashedPassword($data['password']),
            new DateTimeImmutable($data['created_at'])
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return [
            'id' => $this->id->uuid,
            'email' => $this->email->email,
            'password' => $this->password->password,
            'created_at' => $this->createdAt->dateTime,
        ];
    }
}
