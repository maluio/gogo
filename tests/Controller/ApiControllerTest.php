<?php

namespace App\Tests\Controller;

use App\DataFixtures\ItemFixtures;

class ApiControllerTest extends AbstractWebTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures(array(
            ItemFixtures::class
        ));
    }

    public function testRate()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $client->request('GET', '/api/items?due=true');

        $json = $client->getResponse()->getContent();

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $data = json_decode($json, true);

        $this->assertEquals(
            10,
            count($data)
        );

        $body = json_encode(['learn_rating' => 5]);
        $client->request('POST', '/api/items/1/rate', [], [], [], $body);
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertContains(
            'Item due',
                    $client->getResponse()->getContent()
        );


        $client->request('GET', '/api/items?due=true');

        $json = $client->getResponse()->getContent();

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $data = json_decode($json, true);

        $this->assertEquals(
            9,
            count($data)
        );

    }

    public function testGetItems()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $client->request('GET', '/api/items');

        $json = $client->getResponse()->getContent();

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $data = json_decode($json, true);

        $this->assertEquals(
            10,
            count($data)
        );

        $this->assertTrue(
            isset($data[0]['id'])
        );

        $this->assertTrue(
            isset($data[0]['html'])
        );

        $this->assertTrue(
            isset($data[0]['html']['categories'])
        );

        $this->assertTrue(
            isset($data[0]['html']['rating_indicator'])
        );

        $this->assertTrue(
            isset($data[0]['html']['question'])
        );

        $this->assertTrue(
            isset($data[0]['html']['question_masked'])
        );

        $this->assertTrue(
            isset($data[0]['html']['answer'])
        );
    }
}
