<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function jsonRequest(string $method, string $url, ?array $data = null): string
    {
        $this->client->request($method, $url, [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT' => 'application/json',
        ], $data ? json_encode($data) : null);

        return $this->client->getResponse()->getContent();
    }

  /**
   * Vérifie si on a un message de succès.
   */
    public function expectAlert(string $type): void
    {
        $this->assertEquals(1, $this->client->getCrawler()->filter("alert-message[type=\"$type\"], alert-floating[type=\"$type\"]")->count());
    }

  /**
   * Vérifie si on a un message d'erreur.
   */
    public function expectErrorAlert(): void
    {
        $this->assertEquals(1, $this->client->getCrawler()->filter('alert-message[type="danger"], alert-message[type="error"]')->count());
    }

  /**
   * Vérifie si on a un message de succès.
   */
    public function expectSuccessAlert(): void
    {
        $this->expectAlert('success');
    }

    public function expectFormErrors(?int $expectedErrors = null): void
    {
        if (null === $expectedErrors) {
            $this->assertTrue($this->client->getCrawler()->filter('.form-error')->count() > 0, 'Form errors missmatch.');
        } else {
            $this->assertEquals($expectedErrors, $this->client->getCrawler()->filter('.form-error')->count(), 'Form errors missmatch.');
        }
    }

    public function expectH1(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title,
            $crawler->filter('h1')->text(),
            '<h1> mismatch'
        );
    }

    public function expectTitle(string $title): void
    {
        $crawler = $this->client->getCrawler();
        $this->assertEquals(
            $title . ' | Linkmat',
            $crawler->filter('title')->text(),
            '<title> mismatch',
        );
    }


    public function setCsrf(string $key): string
    {
        $csrf = uniqid();
        self::$container->get(TokenStorageInterface::class)->setToken($key, $csrf);

        return $csrf;
    }
}
