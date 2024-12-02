<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use ProxyAssert\Assertion;
use Shared\Domain\DateTimeImmutable;
use Shared\Domain\DomainEventInterface;
use Shared\Domain\Email;
use Shared\Domain\Uuid;

final readonly class UserEmailWasChanged implements DomainEventInterface
{
    public function __construct(
        public Uuid $id,
        public Email $email,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    #[\Override]
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'email');
        Assertion::keyExists($data, 'updated_at');

        return new self(
            new Uuid($data['id']),
            new Email($data['email']),
            new DateTimeImmutable($data['updated_at'])
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return [
            'id' => $this->id->uuid,
            'email' => $this->email->email,
            'updated_at' => $this->updatedAt->dateTime,
        ];
    }
}
