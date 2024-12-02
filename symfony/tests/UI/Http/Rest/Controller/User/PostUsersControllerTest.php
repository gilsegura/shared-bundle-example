<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller\User;

use Tests\UI\Http\Rest\Controller\AbstractJsonApiTestCase;

final class PostUsersControllerTest extends AbstractJsonApiTestCase
{
    public function test_must_post_should_response_204(): void
    {
        $this->post('/v1/users', [
            'data' => [
                'id' => '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3',
                'type' => 'users',
                'attributes' => [
                    'email' => 'johndoe@email.com',
                    'password' => 'password',
                ],
            ],
        ]);

        self::assertSame(204, $this->cli?->getResponse()->getStatusCode());
    }
}
