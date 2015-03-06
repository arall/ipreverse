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
            $this->execute();
        }

        // Invalid domain
        if (!$this->ip) {
            throw new \InvalidArgumentException('Invalid IP');
        }
    }

    /**
     * Query resolver server
     *
     * @throws ResolveErrorException If the resolver doesn't response
     * @return bool
     */
    private function execute()
    {
        try {
            $url = 'http://bgp.he.net/ip/'.$this->ip.'#_dns';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,               $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
            curl_setopt($ch, CURLOPT_USERAGENT,         'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.36');
            curl_setopt($ch, CURLOPT_AUTOREFERER,       true);
            $result = curl_exec($ch);

            file_put_contents('log.txt', $result);

        } catch (ResolveErrorException $e) {
            return false;
        }
    }
}
