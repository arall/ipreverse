<?php

namespace Arall\IPReverse\Servers;

interface Server
{
    public function execute($ch, $ip);
}