<?php

namespace App\Tests\Controller;

use App\DataFixtures\ItemFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class LearnControllerTest extends WebTestCase
{

    public static function getPhpUnitXmlDir(){
        return getenv('KERNEL_DIR');
    }

    public function testIndexWithoutDueItems()
    {
        $this->loadFixtures(array(
        ));

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $this->assertContains(
            'Nothing to learn',
            $client->getResponse()->getContent()
        );

    }

    public function testIndexShowsItemCount()
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
}
