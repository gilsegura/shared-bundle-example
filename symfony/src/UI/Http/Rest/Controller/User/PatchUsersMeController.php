<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\User;

use App\User\Application\Command\ChangeUserEmail\ChangeUserEmailCommand;
use App\User\Application\Command\ChangeUserPassword\ChangeUserPasswordCommand;
use App\User\Domain\Exception\AuthenticationUserException;
use App\User\Domain\Exception\UserEmailAlreadyExistsException;
use App\User\Domain\Exception\UserNotFoundException;
use Shared\Domain\Email;
use Shared\Domain\Uuid;
use SharedBundle\UI\Http\Rest\Controller\AbstractCommandController;
use SharedBundle\UI\Http\Rest\Request\CommandRequester;
use SharedBundle\UI\Http\Rest\Request\Input;
use SharedBundle\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Request\Auth\ChangeUserEmailRequest;
use UI\Http\Rest\Request\Auth\ChangeUserPasswordRequest;

final readonly class PatchUsersMeController extends AbstractCommandController
{
    #[Route('/users/me', name: 'api_patch_users_me', methods: ['PATCH'])]
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: sample id
        $id = '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3';

        $data = $request->request->all();
        $input = Input::deserialize($data);

        $context = new CommandRequester(
            new ChangeUserEmailRequest(
                fn (string $email) => $this->handle(new ChangeUserEmailCommand(new Uuid($id), new Email($email)))
            ),
            new ChangeUserPasswordRequest(
                fn (string $oldPlainPassword, string $plainPassword) => $this->handle(new ChangeUserPasswordCommand(new Uuid($id), $oldPlainPassword, $plainPassword))
            )
        );

        $context->request($input);

        return OpenApi::empty(OpenApi::HTTP_NO_CONTENT);
    }

    #[\Override]
    final protected function exceptions(): array
    {
        return [
            AuthenticationUserException::class => Response::HTTP_UNAUTHORIZED,
            UserNotFoundException::class => Response::HTTP_NOT_FOUND,
            UserEmailAlreadyExistsException::class => Response::HTTP_CONFLICT,
        ];
    }
}
