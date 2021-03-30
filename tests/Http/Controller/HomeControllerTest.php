<?php

namespace App\Tests\Http\Controller;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{

    public function testGetHomeWithGoodResponse()
    {

        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetHomeWithGoodTitle()
    {

        $this->client->request('GET', '/');
        $this->expectTitle('Linkmat');
    }
}
