<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller\User;

use SharedBundle\UI\Http\Rest\Response\OpenApi;
use Tests\UI\Http\Rest\Controller\AbstractJsonApiTest;

final class PostUsersControllerTest extends AbstractJsonApiTest
{
    public function test_(): void
    {
        $this->post('/api/users', [
            'id' => '7ea24ad8-2d4b-46c5-8d2e-03b34ab420c3',
            'email' => 'johndoe@email.com',
            'plain_password' => 'password',
        ]);

        self::assertSame(OpenApi::HTTP_CREATED, $this->cli->getResponse()->getStatusCode());
    }
}
