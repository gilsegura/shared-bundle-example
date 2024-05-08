<?php

declare(strict_types=1);

namespace UI\Http\Rest\Request;

use Assert\Assertion;
use SharedBundle\UI\Http\Rest\Request\Input;
use SharedBundle\UI\Http\Rest\Request\RequestInterface;

final readonly class ChangeUserPasswordRequest implements RequestInterface
{
    public function __construct(
        private \Closure $callable
    ) {
    }

    #[\Override]
    public function support(Input $input): bool
    {
        return $input->has('old_plain_password')
            && $input->has('plain_password');
    }

    #[\Override]
    public function doWithRequest(Input $input): void
    {
        $oldPlainPassword = $input->get('old_plain_password');
        $plainPassword = $input->get('plain_password');

        Assertion::notEmpty($oldPlainPassword);
        Assertion::notEmpty($plainPassword);

        call_user_func($this->callable, $oldPlainPassword, $plainPassword);
    }
}
