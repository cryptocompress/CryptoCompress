<?php

use \CryptoCompress\WebService\Yahoo\Weather,
	\CryptoCompress\WebService\Twitter,
	\CryptoCompress\Http\Curl\Connection,
	\CryptoCompress\Http\Curl\Pool,
    \CryptoCompress\Http\Curl\Response,
    \CryptoCompress\Http\Curl\Request\Get,
    \CryptoCompress\Http\Curl\Request\Post;


class WeatherTest extends \PHPUnit_Framework_TestCase {

	public function testArrayKeys() {
		$config = array(
			'url'		=> 'http://weather.yahooapis.com/forecastrss',
			'degree'	=> Weather::DEGREE_CELSIUS,
		);

		$weatherClient		= new Weather($config, new Connection());
		$weatherDocument	= $weatherClient->getByCode(Weather::CODE_MUNICH);

		$this->assertArrayHasKey('temp',		$weatherDocument->current());
		$this->assertArrayHasKey('text',		$weatherDocument->current());

		$this->assertArrayHasKey('text',		$weatherDocument->forecast());
		$this->assertArrayHasKey('high',		$weatherDocument->forecast());
		$this->assertArrayHasKey('low',			$weatherDocument->forecast());

		$this->assertArrayHasKey('humidity',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('visibility',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('pressure',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('rising',		$weatherDocument->atmosphere());
	}

	public function testArrayKeysWithPool() {
		$config = array(
			'url'		=> 'http://weather.yahooapis.com/forecastrss',
			'degree'	=> Weather::DEGREE_CELSIUS,
		);

		$weatherClient		= new Weather($config, new Pool());
		$weatherDocument	= $weatherClient->getByCode(Weather::CODE_MUNICH);

		$this->assertArrayHasKey('temp',		$weatherDocument->current());
		$this->assertArrayHasKey('text',		$weatherDocument->current());

		$this->assertArrayHasKey('text',		$weatherDocument->forecast());
		$this->assertArrayHasKey('high',		$weatherDocument->forecast());
		$this->assertArrayHasKey('low',			$weatherDocument->forecast());

		$this->assertArrayHasKey('humidity',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('visibility',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('pressure',	$weatherDocument->atmosphere());
		$this->assertArrayHasKey('rising',		$weatherDocument->atmosphere());
	}

	public function testTwitterPost() {
		$pool = new Pool();

		$configYahooWeather = array(
			'url'		=> 'http://weather.yahooapis.com/forecastrss',
			'degree'	=> Weather::DEGREE_CELSIUS,
		);

		$configTwitter = array(
			'strict'	=> false,
			'format'	=> 'json', # json, xml, rss, atom
			'oauth'		=> array(
				'consumer_key'			=> 'zw9T8W42BBQFwRvJXMw',
				'consumer_secret'		=> 'qe3IiWO4KWLK41Em559NMDuvDafv1kXwvbq0lUJQWAA',
				'access_token'			=> '582166477-sQNHyG7vkNX80GF0TptiN7Ui53nrUcM618XCxANc',
				'access_token_secret'	=> 'oqkSS8VPp3QKxGA4KOQi6lCwRIHhlfkC6xaB6I3jA',
				'signature_method'		=> 'HMAC-SHA1',
				'version'				=> '1.0',
			)
		);

		$yahooWeather	= new Weather($configYahooWeather, $pool);
		$weatherY   	= $yahooWeather->getByCode(Weather::CODE_MUNICH);
		$current		= $weatherY->current();
		$today			= $weatherY->forecast(0);
		$tomorrow		= $weatherY->forecast(1);

		$text	= 'Jetzt: ' . $current['temp'] . '°C ' . $current['text'] . " (" . $weatherY->atmosphere('humidity') . '% Lf.)' . "\n"
				. 'Heute: ' . $today['low'] . ' bis ' . $today['high'] . '°C ' . $today['text'] . '' . "\n"
				. 'Morgen: ' . $tomorrow['low'] . ' bis ' . $tomorrow['high'] . '°C ' . $tomorrow['text'] . ' ‪' . "\n"
				. '#münchen‬ ‪#wetter‬';

		sleep(1);

		$twitter = new Twitter($configTwitter, $pool);

		$timeline = $twitter->timelineUser(array('count' => 1, 'exclude_replies' => true));
		if (!$timeline->isEmpty() && $timeline->first()->timestamp() > time() - 3600) {
			#die('Aktuelles Wetter vor weniger als einer Stunde gepostet!');
		}

		$text = microtime(true) . ' ' . $text;

		while (mb_strlen($text) > 140) {
		    $text = preg_replace('/\s+\S+$/u', '', $text);
		}

		$twitter->reply($text)->text();

		$this->assertEquals($text, $text);
	}
}
