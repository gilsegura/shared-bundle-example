<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\User;

use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\User\Domain\Exception\UserEmailAlreadyExistsException;
use Assert\Assertion;
use Shared\Domain\Email;
use Shared\Domain\Uuid;
use SharedBundle\UI\Http\Rest\Controller\AbstractCommandController;
use SharedBundle\UI\Http\Rest\Request\Input;
use SharedBundle\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final readonly class PostUsersController extends AbstractCommandController
{
    #[Route('/users', name: 'api_post_users', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $input = Input::deserialize($data);

        $id = $input->get('id');
        $email = $input->get('email');
        $plainPassword = $input->get('plain_password');

        Assertion::notEmpty($id);
        Assertion::notEmpty($email);
        Assertion::notEmpty($plainPassword);

        // TODO: sample id
        $id = '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3';

        $this->handle(new CreateUserCommand(
            new Uuid($id),
            new Email($email),
            $plainPassword
        ));

        return OpenApi::created();
    }

    #[\Override]
    final protected function exceptions(): array
    {
        return [
            UserEmailAlreadyExistsException::class => Response::HTTP_CONFLICT,
        ];
    }
}
