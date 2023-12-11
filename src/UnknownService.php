<?php

namespace Arall;

use Exception;

class UnknownService extends Exception
{

    /**
     * @param $serverName
     */
    public function __construct($serverName)
    {
        parent::__construct("$serverName is not available yet");
    }
}