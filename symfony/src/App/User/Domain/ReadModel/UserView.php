<?php

declare(strict_types=1);

namespace App\User\Domain\ReadModel;

use Shared\Domain\DateTimeImmutable;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use Shared\ReadModel\SerializableInterfaceReadModelInterface;

final class UserView implements SerializableInterfaceReadModelInterface
{
    private function __construct(
        public Uuid $id,
        public Email $email,
        public HashedPassword $password,
        public DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt
    ) {
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

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
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
}
