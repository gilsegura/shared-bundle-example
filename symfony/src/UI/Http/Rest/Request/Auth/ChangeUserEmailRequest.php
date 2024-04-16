<?php

declare(strict_types=1);

namespace UI\Http\Rest\Request\Auth;

use Assert\Assertion;
use SharedBundle\UI\Http\Rest\Request\Input;
use SharedBundle\UI\Http\Rest\Request\RequestInterface;

final readonly class ChangeUserEmailRequest implements RequestInterface
{
    public function __construct(
        private \Closure $callable
    ) {
    }

    #[\Override]
    public function support(Input $input): bool
    {
        return $input->has('email');
    }

    #[\Override]
    public function doWithRequest(Input $input): void
    {
        $email = $input->get('email');

        Assertion::notEmpty($email);

        call_user_func($this->callable, $email);
    }
}
