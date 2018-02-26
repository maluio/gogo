<?php


namespace App\Tests\Controller;

use App\DataFixtures\ItemFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;


abstract class AbstractWebTestCase extends WebTestCase
{
    public static function getPhpUnitXmlDir(){
        return getenv('KERNEL_DIR');
    }

}