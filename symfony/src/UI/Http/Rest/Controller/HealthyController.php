<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller;

use SharedBundle\AMQP\AMQPHealthyConnection;
use SharedBundle\DBAL\DBALHealthyConnection;
use SharedBundle\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final readonly class HealthyController
{
    public function __construct(
        private DBALHealthyConnection $DBALHealthyConnection,
        private AMQPHealthyConnection $AMQPHealthyConnection,
    ) {
    }

    #[Route('/healthy', name: 'healthy', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dbal = $this->DBALHealthyConnection->isHealthy();
        $amqp = $this->AMQPHealthyConnection->isHealthy();

        if (
            $dbal
            && $amqp
        ) {
            return OpenApi::empty();
        }

        return OpenApi::fromPayload([
            'healthy_services' => [
                'mysql' => $dbal,
                'rabbitmq' => $amqp,
            ],
        ], OpenApi::HTTP_INTERNAL_SERVER_ERROR);
    }
}
