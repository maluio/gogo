<?php

namespace App\Tests\Controller;

use App\DataFixtures\ItemFixtures;

class ItemControllerTest extends AbstractWebTestCase
{

    const FIXTURES_NUMBER_OF_ITEMS = 10;

    public function testCreate()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/admin/create');

        $form = $crawler->selectButton('create')->form();

        $client->submit($form, array(
            'item[question]'=> 'foo',
            'item[answer]'  => 'bar'
        ));

        $this->assertStatusCode(302, $client);

        $crawler = $client->request('GET', '/admin/');

        $this->assertEquals(
            self::FIXTURES_NUMBER_OF_ITEMS + 1,
            $crawler->filter('span.due')->count()
        );
    }

    public function testEdit()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/admin/1/edit');

        $form = $crawler->selectButton('update')->form();

        $client->submit($form, array(
            'item[question]'=> 'foo',
            'item[answer]'  => 'bar'
        ));

        $this->assertStatusCode(302, $client);

        $crawler = $client->request('GET', '/admin/');

        $this->assertEquals(
            self::FIXTURES_NUMBER_OF_ITEMS,
            $crawler->filter('span.due')->count()
        );
    }

    public function testList()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/admin/');

        $this->assertStatusCode(200, $client);

        $this->assertEquals(
            self::FIXTURES_NUMBER_OF_ITEMS,
            $crawler->filter('span.due')->count()
        );

    }

    public function testDelete()
    {
        $this->loadFixtures(array(
            ItemFixtures::class
        ));

        $client = $this->makeClient();

        $crawler = $client->request('POST', '/admin/1/delete');

        $this->assertStatusCode(302, $client);

        $crawler = $client->request('GET', '/admin/');

        $this->assertEquals(
            self::FIXTURES_NUMBER_OF_ITEMS - 1,
            $crawler->filter('span.due')->count()
        );
    }
}
