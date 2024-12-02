<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel;

use Serializer\SerializableInterface;
use Shared\Domain\DateTimeImmutable;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use Shared\ReadModel\SerializableReadModelInterface;

final class User implements SerializableReadModelInterface
{
    private function __construct(
        private Uuid $id,
        private Email $email,
        private HashedPassword $password,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function fromSerializable(SerializableInterface $serializable): self
    {
        return self::deserialize($serializable->serialize());
    }

    #[\Override]
    public static function deserialize(array $data): self
    {
        return new self(
            new Uuid($data['id']),
            new Email($data['email']),
            new HashedPassword($data['password']),
            new DateTimeImmutable($data['created_at']),
            isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return [
            'id' => $this->id->uuid,
            'email' => $this->email->email,
        ];
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function changeUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[\Override]
    public function id(): Uuid
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
