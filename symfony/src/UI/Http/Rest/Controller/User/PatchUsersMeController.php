<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\User;

use App\User\Application\Command\ChangeUserEmail\ChangeUserEmailCommand;
use App\User\Application\Command\ChangeUserPassword\ChangeUserPasswordCommand;
use App\User\Domain\Exception\AuthenticationUserException;
use App\User\Domain\Exception\UserEmailAlreadyExistsException;
use App\User\Domain\Exception\UserNotFoundException;
use Shared\Domain\Email;
use Shared\Domain\HashedPassword;
use Shared\Domain\Uuid;
use SharedBundle\UI\Http\Rest\Controller\AbstractCommandController;
use SharedBundle\UI\Http\Rest\Request\InputBag;
use SharedBundle\UI\Http\Rest\Request\SequentialCommanderChain;
use SharedBundle\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Request\ChangeUserEmail;
use UI\Http\Rest\Request\ChangeUserPassword;

final readonly class PatchUsersMeController extends AbstractCommandController
{
    #[Route('/users/me', name: 'api_patch_users_me', methods: ['PATCH'])]
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: sample id
        $id = '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3';

        $data = $request->request->all();
        $input = InputBag::deserialize($data);

        $commander = new SequentialCommanderChain(
            new ChangeUserEmail(
                fn (string $email) => $this->handle(new ChangeUserEmailCommand(
                    new Uuid($id), new Email($email)
                ))
            ),
            new ChangeUserPassword(
                fn (string $oldPlainPassword, string $plainPassword) => $this->handle(new ChangeUserPasswordCommand(
                    new Uuid($id), $oldPlainPassword, HashedPassword::encode($plainPassword)
                ))
            )
        );

        $commander->doWithRequest($input);

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
