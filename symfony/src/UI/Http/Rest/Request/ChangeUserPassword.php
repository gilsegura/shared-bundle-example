<?php

declare(strict_types=1);

namespace UI\Http\Rest\Request;

use Assert\Assertion;
use SharedBundle\UI\Http\Rest\Request\CommanderInterface;
use SharedBundle\UI\Http\Rest\Request\InputBag;

final readonly class ChangeUserPassword implements CommanderInterface
{
    public function __construct(
        private \Closure $callable,
    ) {
    }

    #[\Override]
    public function support(InputBag $input): bool
    {
        return $input->has('old_plain_password')
            && $input->has('plain_password');
    }

    #[\Override]
    public function doWithRequest(InputBag $input): void
    {
        $oldPlainPassword = $input->get('old_plain_password');
        $plainPassword = $input->get('plain_password');

        Assertion::notEmpty($oldPlainPassword);
        Assertion::notEmpty($plainPassword);

        call_user_func($this->callable, $oldPlainPassword, $plainPassword);
    }
}
