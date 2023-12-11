<?php

namespace Arall\IPReverse\Servers;

use DomDocument;

class Bing implements Server
{

    public function execute($ch, $ip)
    {
        $url = 'http://www.bing.com/search?q=ip%3a'.$ip;
        curl_setopt($ch, CURLOPT_URL,           $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,    array("Cookie: SRCHHPGUSR=NRSLT=500"));
        $result = curl_exec($ch);

        $hosts = array();

        $doc = new DomDocument();
        if ($doc->loadHTML($result)) {
            foreach ($doc->getElementById('b_results')->getElementsByTagName('li') as $elements) {
                foreach ($elements->getElementsByTagName('cite') as $element) {
                    if (preg_match('/((\w+\.)?[a-zA-Z0-9\-]+\.[a-z]+)/', $element->textContent, $matches)) {
                        $hosts[$matches[1]] = $matches[1];
                    }
                }
            }
        }

        return $hosts;
    }
}