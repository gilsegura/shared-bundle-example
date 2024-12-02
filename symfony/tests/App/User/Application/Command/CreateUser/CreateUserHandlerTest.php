<?php

declare(strict_types=1);

namespace Tests\App\User\Application\Command\CreateUser;

use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\User\Domain\Event\UserWasCreated;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use Tests\App\AbstractApplicationTestCase;
use Tests\App\EventCollectorListener;

final class CreateUserHandlerTest extends AbstractApplicationTestCase
{
    public function test_must_create_an_user(): void
    {
        $command = new CreateUserCommand(
            new Uuid('7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3'),
            new Email('johndoe@email.com'),
            HashedPassword::encode('password'),
        );

        $this->handle($command);

        /** @var EventCollectorListener $collector */
        $collector = self::getContainer()->get(EventCollectorListener::class);
        $messages = $collector->messages();

        self::assertInstanceOf(UserWasCreated::class, $messages[0]->payload);
    }

    // TODO ...
}
