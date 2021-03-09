<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class PantherDefaultControllerTest extends PantherTestCase
{
    public function testIndex(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');


        $client->takeScreenshot('reportScreen/PantherDefaultControllerTest/testIndex_0.png');
        $this->assertSelectorTextContains('.navbar-brand', 'CW24 Symfony');
        $this->assertSelectorTextContains('.navbar-nav li:last-child', 'Login');
        $this->assertPageTitleContains('Hello DefaultController!');
    }

    public function testGoToSignIn(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        $link   = $crawler->selectLink('Login')->link();
        $crawler = $client->click($link);

        $client->takeScreenshot('reportScreen/PantherDefaultControllerTest/testGoToSignIn_0.png');
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }
}
