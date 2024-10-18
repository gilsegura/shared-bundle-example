<?php

declare(strict_types=1);

namespace App\User\Application\Command\ChangeUserPassword;

use Shared\CommandHandling\CommandInterface;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;

final readonly class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public string $oldPlainPassword,
        public HashedPassword $password,
    ) {
    }
}
