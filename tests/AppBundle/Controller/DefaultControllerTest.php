<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Home', $crawler->filter('#container h1')->text());
        //$this->assertContains($crawler->filter('html:contains("Hotels")')->count() >0 );
        //$this->assertEquals(4,3+1);
    }
}
