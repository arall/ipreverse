<?php

namespace Arall;

class IpReverse
{

    /**
	 * IP
	 *
	 * @var string
	 */
    private $ip;

    /**
     * Found hosts
     *
     * @var array
     */
    public $hosts;

    /**
	 * Construct
     *
	 * @param string $ip
     * @throws InvalidArgumentException If the IP is not valid
	 */
    public function __construct($ip)
    {
        // Is valid?
        if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/", $ip)) {

            // Store
            $this->ip = $ip;

            // Run
            $this->execute('hurricane');
        }

        // Invalid domain
        if (!$this->ip) {
            throw new \InvalidArgumentException('Invalid IP');
        }
    }

    /**
     * Query resolver switcher
     *
     * @return bool
     */
    private function execute($server)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
            curl_setopt($ch, CURLOPT_USERAGENT,         'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.36');
            curl_setopt($ch, CURLOPT_AUTOREFERER,       true);
            switch ($server) {
                case 'hurricane':
                        $this->executeHurricane($ch);
                    break;
                default:
                        $this->executeBing($ch);
                    break;
            }
        } catch (ResolveErrorException $e) {
            return false;
        }
        return true;
    }

    /**
     * Hurricane query resolver server 
     *
     * @throws ResolveErrorException If the resolver doesn't response
     */
    private function executeHurricane($ch)
    {
        $url = 'http://bgp.he.net/ip/'.$this->ip.'#_dns';
        curl_setopt($ch, CURLOPT_URL,               $url);
        $result = curl_exec($ch);

        $this->hosts = array();

        $doc = new \DomDocument;
        if($doc->loadHTML($result)) {
            foreach($doc->getElementById('dns')->getElementsByTagName('a') as $element) {
                if(preg_match('/^\w+\.[a-z]+$/', $element->textContent)) {
                    $this->hosts[$element->textContent] = $element->textContent;
                }
            }
        } else {
            throw new ResolveErrorException;
        }
    }

    /**
     * Bing query resolver server
     *
     * @throws ResolveErrorException If the resolver doesn't response
     */
    private function executeBing($ch)
    {
        $url = 'http://www.bing.com/search?q=ip%3a'.$this->ip;
        curl_setopt($ch, CURLOPT_URL,               $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,        array("Cookie: SRCHHPGUSR=NRSLT=500"));
        $result = curl_exec($ch);

        $this->hosts = array();

        $doc = new \DomDocument;
        if($doc->loadHTML($result)) {
            foreach($doc->getElementById('b_results')->getElementsByTagName('li') as $elements) {
                foreach($elements->getElementsByTagName('cite') as $element) {
                    if(preg_match('/((\w+\.)?[a-zA-Z0-9\-]+\.[a-z]+)/', $element->textContent, $matches)) {
                        $this->hosts[$matches[1]] = $matches[1];
                    }
                }
            }
        } else {
            throw new ResolveErrorException;
        }
    }
}
