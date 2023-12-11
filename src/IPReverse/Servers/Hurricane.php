<?php

namespace Arall\IPReverse\Servers;

use DomDocument;

class Hurricane implements Server
{

    public function execute($ch, $ip)
    {
        $url = 'http://bgp.he.net/ip/'.$ip.'#_dns';
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);

        $hosts = array();

        $doc = new DomDocument();
        if ($doc->loadHTML($result)) {
            foreach ($doc->getElementById('dns')->getElementsByTagName('a') as $element) {
                if (preg_match('/^\w+\.[a-z]+$/', $element->textContent)) {
                    $hosts[$element->textContent] = $element->textContent;
                }
            }
        }

        return $hosts;
    }
}