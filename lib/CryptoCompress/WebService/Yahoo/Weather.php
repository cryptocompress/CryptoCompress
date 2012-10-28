<?php

namespace CryptoCompress\WebService\Yahoo;

use CryptoCompress\Http\Curl\Request\Get;

class Weather {

	const DEGREE_CELSIUS	= 'c';
	const DEGREE_FAHRENHEIT	= 'f';

    const CODE_MUNICH = 676757;

	/**
	 * @var	array
	 */
	protected $config;

	/**
	 * @var	\CryptoCompress\Http\ITransport
	 */
	protected $transport;

	/**
	 * @param	array							$config
	 * @param	\CryptoCompress\Http\ITransport	$transport
	 */
	public function __construct(array $config, \CryptoCompress\Http\ITransport $transport) {
		$this->config		= $config;
		$this->transport	= $transport;
    }

	/**
	 * 'http://weather.yahooapis.com/forecastrss?u=c&w=' . 676757;
	 *
	 * @param	string	$code	e.g. 676757 for munich
	 *
	 * @return	\CryptoCompress\WebService\Yahoo\Weather\Document
	 */
	public function getByCode($code) {
		$get = array(
			'u' => $this->config['degree'],
			'w'	=> $code,
			'd'	=> 2,	// days
		);

        return new Weather\Document($this->transport->fetch(new Get($this->config['url'], $get))->document());
	}
}