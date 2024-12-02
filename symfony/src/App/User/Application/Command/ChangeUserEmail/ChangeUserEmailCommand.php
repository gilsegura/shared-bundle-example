<?php

declare(strict_types=1);

namespace App\User\Application\Command\ChangeUserEmail;

use Shared\CommandHandling\CommandInterface;
use Shared\Domain\Email;
use Shared\Domain\Uuid;

final readonly class ChangeUserEmailCommand implements CommandInterface
{
    public function __construct(
        public Uuid $id,
        public Email $email,
    ) {
    }
}
