<?php

require_once __DIR__.'/../vendor/autoload.php';

use Arall\IpReverse;

class IpReverseTest extends PHPUnit_Framework_TestCase
{

    public function testLookup()
    {
        $ipReverse = new IpReverse('69.50.225.155');
        //TODO
    }
}
