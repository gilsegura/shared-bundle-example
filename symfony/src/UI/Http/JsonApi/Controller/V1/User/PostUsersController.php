<?php

declare(strict_types=1);

namespace UI\Http\JsonApi\Controller\V1\User;

use App\User\Application\Command\CreateUser\CreateUserCommand;
use JsonApi\Server\Response\Response;
use JsonApi\ServerBundle\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\CommandHandling\CommandBusInterface;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users', name: 'json_api_v1_post_users', methods: ['POST'])]
final readonly class PostUsersController extends AbstractController
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    #[\Override]
    public function handler(ServerRequestInterface $request): ResponseInterface
    {
        /** @var object{data: object{id: string, type: string, attributes: object{email:string, password:string}}} $body */
        $body = json_decode($request->getBody()->__toString());

        $this->commandBus->__invoke(new CreateUserCommand(
            new Uuid($body->data->id),
            new Email($body->data->attributes->email),
            HashedPassword::encode($body->data->attributes->password),
        ));

        return Response::noContent();
    }
}
