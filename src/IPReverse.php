<?php

namespace Arall;

use InvalidArgumentException;

class IPReverse
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
     */
    public function __construct($ip)
    {
        // Is valid?
        $this->validateIP($ip);
    }

    /**
     * @throws UnknownService
     */
    private static function getServerBy($serverName)
    {
        $serverClass = 'Arall\IPReverse\Servers\\' . ucfirst($serverName);
        if (class_exists($serverClass)) {

            return new $serverClass();
        }

        throw new UnknownService($serverName);
    }

    /**
     * Query resolver switcher
     *
     * @param string $serverName
     * @return boolean
     * @throws UnknownService
     */
    public function execute($serverName = 'bing')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($ch, CURLOPT_USERAGENT,         'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.36');
        curl_setopt($ch, CURLOPT_AUTOREFERER,       true);

        $server = self::getServerBy($serverName);

        return $server->execute($ch, $this->ip);
    }

    /**
     * @param $ip
     * @return void
     */
    protected function validateIP($ip)
    {
        if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/", $ip)) {

            // Store
            $this->ip = $ip;

        } else {

            // Invalid domain
            throw new InvalidArgumentException('Invalid IP');
        }
    }
}
