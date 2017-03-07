<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CityControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/city/');
        $this->assertEquals(500, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /city/");
        $crawler = $client->click($crawler->selectLink('Create New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'app1_examplebundle_city[city]'  => 'Kalutara',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'app1_examplebundle_city[city]'  => 'Kalutara',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Kalutara"]')->count(), 'Missing element [value="Kalutara"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

    }

    
}
