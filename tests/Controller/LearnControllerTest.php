<?php

namespace App\Tests\Controller;

use App\DataFixtures\ItemFixtures;

class LearnControllerTest extends AbstractWebTestCase
{

    public function testNoDueItems()
    {
        $this->loadFixtures(array(
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $this->assertContains(
            '<span class="oi oi-check"></span>',
            $client->getResponse()->getContent()
        );

    }

    public function testShowItemCount()
    {

        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(
            10,
            trim($crawler->filter('span.due-item-count')->text())
        );
    }

    public function testLearnItem()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('POST', '/learn/rate/1', ['learn_rating' => 3]);

        $this->assertStatusCode(302, $client);

    }
}
