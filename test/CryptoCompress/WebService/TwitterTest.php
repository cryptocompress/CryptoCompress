<?php

use	\CryptoCompress\WebService\Yahoo\Weather,
	\CryptoCompress\WebService\Twitter,
	\CryptoCompress\Http\Curl\Connection,
	\CryptoCompress\Http\Curl\Pool,
	\CryptoCompress\Http\Curl\Response,
	\CryptoCompress\Http\Curl\Request\Get,
	\CryptoCompress\Http\Curl\Request\Post;

class TwitterTest extends \PHPUnit_Framework_TestCase {

	protected $configTwitter = array(
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

	public function testTimeline() {
		$pool = new Pool();

		$twitter = new Twitter($this->configTwitter, $pool);

		$timeline = $twitter->timelineUser(array('count' => 1, 'exclude_replies' => true));

		$this->assertInstanceOf('CryptoCompress\WebService\Twitter\User\TimeLine', $timeline);
		$this->assertGreaterThan(0, count($timeline));
		$this->assertGreaterThan(0, substr($timeline->first()->id(), 0, 1));
	}

	public function testReply() {
		$pool = new Pool();

		$twitter = new Twitter($this->configTwitter, $pool);

		$text = __METHOD__ . ' ' . microtime(true) . ' ' . php_uname();

		$ret = $twitter->reply($text)->text();

		$this->assertEquals(trim(substr($text, 0, 140)), $ret);
	}

}
