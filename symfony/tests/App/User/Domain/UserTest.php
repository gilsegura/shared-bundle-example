<?php

declare(strict_types=1);

namespace Tests\App\User\Domain;

use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\User;
use PHPUnit\Framework\TestCase;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;

final class UserTest extends TestCase implements UniqueEmailSpecificationInterface
{
    private bool $isUnique = true;

    public function test_must_throw_user_email_already_exists_exception(): void
    {
        self::expectException(UserAlreadyExistsException::class);

        $this->isUnique = false;

        User::create(
            new Uuid('7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3'),
            new Email('johndoe@email.com'),
            HashedPassword::encode('password'),
            $this
        );
    }

    public function test_must_create_an_user(): void
    {
        $user = User::create(
            new Uuid('7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3'),
            new Email('johndoe@email.com'),
            HashedPassword::encode('password'),
            $this
        );

        $stream = $user->uncommittedEvents();
        $messages = $stream->messages;

        self::assertInstanceOf(UserWasCreated::class, $messages[0]->payload);
    }

    // TODO ...

    #[\Override]
    public function __invoke(Email $email): bool
    {
        if (!$this->isUnique) {
            throw new UserAlreadyExistsException();
        }

        return true;
    }
}
