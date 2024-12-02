<?php

declare(strict_types=1);

namespace Tests\App;

use Shared\Domain\DomainMessage;
use Shared\EventHandling\EventListenerInterface;

final class EventCollectorListener implements EventListenerInterface
{
    /** @var DomainMessage[] */
    private array $messages = [];

    #[\Override]
    public function __invoke(DomainMessage $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return DomainMessage[]
     */
    public function messages(): array
    {
        $events = $this->messages;

        $this->messages = [];

        return $events;
    }
}
