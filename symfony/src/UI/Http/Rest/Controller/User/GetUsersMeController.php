<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\User;

use App\User\Application\Query\GetUser\GetUserQuery;
use App\User\Domain\Exception\UserNotFoundException;
use Shared\Domain\Uuid;
use SharedBundle\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final readonly class GetUsersMeController extends AbstractQueryController
{
    #[Route('/users/me', name: 'api_get_users_me', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: sample id
        $id = '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3';

        $item = $this->ask(new GetUserQuery(new Uuid($id)));

        return $this->json($item);
    }

    #[\Override]
    final protected function exceptions(): array
    {
        return [
            UserNotFoundException::class => Response::HTTP_NOT_FOUND,
        ];
    }
}
