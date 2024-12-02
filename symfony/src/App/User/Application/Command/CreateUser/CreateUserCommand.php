<?php

declare(strict_types=1);

namespace App\User\Application\Command\CreateUser;

use Shared\CommandHandling\CommandInterface;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;

final readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public Email $email,
        public HashedPassword $password,
    ) {
    }
}
