<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractJsonApiTestCase extends WebTestCase
{
    protected ?KernelBrowser $cli;

    #[\Override]
    protected function setUp(): void
    {
        $this->cli = static::createClient();
    }

    final public function post(string $uri, array $params = [], array $headers = []): void
    {
        $this->cli?->request(
            'POST',
            $uri,
            [],
            [],
            array_merge($this->headers(), ['CONTENT_TYPE' => 'application/vnd.api+json;ext="https://jsonapi.org/ext/version"'], $headers),
            (string) json_encode($params)
        );
    }

    final public function delete(string $uri, array $params = [], array $headers = []): void
    {
        $this->cli?->request(
            'DELETE',
            $uri,
            $params,
            [],
            array_merge($this->headers(), ['CONTENT_TYPE' => 'application/json'], $headers),
        );
    }

    final public function patch(string $uri, array $params = [], array $headers = []): void
    {
        $this->cli?->request(
            'PATCH',
            $uri,
            [],
            [],
            array_merge($this->headers(), ['CONTENT_TYPE' => 'application/json'], $headers),
            (string) json_encode($params)
        );
    }

    final public function get(string $uri, array $params = [], array $headers = []): void
    {
        $this->cli?->request(
            'GET',
            $uri,
            $params,
            [],
            array_merge($this->headers(), $headers)
        );
    }

    private function headers(): array
    {
        return [
            'HTTP_ACCEPT' => 'application/vnd.api+json;ext="https://jsonapi.org/ext/version"',
        ];
    }

    final public function fireTerminateEvents(): void
    {
        $kernel = self::$kernel;

        if (
            !$kernel instanceof KernelInterface
            || !self::$booted
        ) {
            $kernel = self::bootKernel();
        }

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->cli?->getContainer()->get('event_dispatcher');

        if (!$dispatcher instanceof EventDispatcher) {
            throw new \RuntimeException(sprintf('The event_dispatcher must be an instance of %s.', EventDispatcher::class));
        }

        $dispatcher->dispatch(
            new TerminateEvent(
                $kernel,
                Request::create('/'),
                new Response()
            ),
            KernelEvents::TERMINATE
        );
    }

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cli = null;
    }
}
