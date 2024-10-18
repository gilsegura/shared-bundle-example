<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetUser;

use Shared\CommandHandling\QueryInterface;
use Shared\Domain\Uuid;

final readonly class GetUserQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
