<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HotelControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/hotel/');
        $this->assertEquals(500, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /hotel/");
        $crawler = $client->click($crawler->selectLink('Create New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create New')->form(array(
            'app1_examplebundle_hotel[name]'  => 'Taj Samudra',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Taj Samudra")')->count(), 'Missing element td:contains("Taj Samudra")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'app1_examplebundle_hotel[name]'  => 'Taj Samudra',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Taj Samudra"]')->count(), 'Missing element [value="Taj Samudra"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

    }

    
}
