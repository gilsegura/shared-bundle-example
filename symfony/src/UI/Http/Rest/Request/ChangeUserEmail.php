<?php

declare(strict_types=1);

namespace UI\Http\Rest\Request;

use Assert\Assertion;
use SharedBundle\UI\Http\Rest\Request\CommanderInterface;
use SharedBundle\UI\Http\Rest\Request\InputBag;

final readonly class ChangeUserEmail implements CommanderInterface
{
    public function __construct(
        private \Closure $callable,
    ) {
    }

    #[\Override]
    public function support(InputBag $input): bool
    {
        return $input->has('email');
    }

    #[\Override]
    public function doWithRequest(InputBag $input): void
    {
        $email = $input->get('email');

        Assertion::notEmpty($email);

        call_user_func($this->callable, $email);
    }
}
