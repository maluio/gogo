<?php

namespace App\Tests\Controller;

use App\Controller\LearnController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LearnControllerTest extends WebTestCase
{

    public function testIndex()
    {

        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testHandleLearnResult()
    {

    }
}
