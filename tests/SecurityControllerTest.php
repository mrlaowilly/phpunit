<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\Internal\ClientState;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginLogout(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
        $this->assertSelectorTextContains('.navbar-nav li:last-child', 'Login');

        // Login
        $form = $crawler->filter('button[type=submit]')->form();
        $form['email']      = 'admin@yopmail.com';
        $form['password']   = 'azertyuiop';
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.navbar-brand', 'CW24 Symfony');
        $this->assertSelectorTextContains('.navbar-nav li:last-child', 'Logout');
        $this->assertSelectorTextContains('title', 'Hello DefaultController!');

        // Logout
        $crawler = $client->getCrawler();
        $link = $crawler->filter('a:contains("Logout")')->eq(0)->link();
        $crawler = $client->click($link);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.navbar-brand', 'CW24 Symfony');
        $this->assertSelectorTextContains('.navbar-nav li:last-child', 'Login');
        $this->assertSelectorTextContains('title', 'Hello DefaultController!');
    }

    public function testLoginError()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Login
        $form = $crawler->filter('button[type=submit]')->form();
        $form['email']      = 'error@yopmail.com';
        $form['password']   = 'azertyuiop';
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Email could not be found.');
    }

    public function login($login='admin@yopmail.com', $password='azertyuiop', $url='/login')
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        // Login
        $form = $crawler->filter('button[type=submit]')->form();
        $form['email']      = $login;
        $form['password']   = $password;
        $client->submit($form);
        $client->followRedirect();

        return $client;
    }

    public function testGetFormAddArticle() {

        $client = $this->login();

        $crawler = $client->getCrawler();
        $link   = $crawler->filter('a:contains("Article")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Article index');

        $link   = $crawler->filter('a:contains("Create new")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Article');
    }

    /*
    public function testFormAddArticleData() {
        $client = $this->login();
        $crawler = $client->request('GET', '/article/new');
        // todo test form
    }
    */

    public function testCreateArticle(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/article/new');
        $form = $crawler->filter('button.btn.btn-primary')->form();


        // $form['article_name'] = '';
        // $form['article_description'] = '';
        // $form['article_price'] = '';
        $client->submit($form);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label[for=article_name] .form-error-message', 'This value should not be blank');
        $this->assertSelectorTextContains('label[for=article_description] .form-error-message', 'This value should not be blank');
        $this->assertSelectorTextContains('label[for=article_price] .form-error-message', 'This value should not be blank');
    }
}