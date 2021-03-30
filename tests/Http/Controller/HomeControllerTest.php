<?php

namespace App\Tests\Http\Controller;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{

    protected KernelBrowser $client;

    protected function setUp(): void
    {

        $this->client = self::createClient();
    }

    public function testGetHomeWithGoodResponse(): void
    {

        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testGetLayoutWithGoodResponse(): void
    {

        $this->client->request('GET', '/layout');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testGetHomeWithGoodTitle(): void
    {

        $this->client->request('GET', '/');
        $this->expectTitle('Linkmat');
    }
}
