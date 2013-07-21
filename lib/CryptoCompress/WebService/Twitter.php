<?php

/**
 * @link	https://dev.twitter.com/docs/api
 */

namespace CryptoCompress\WebService;

use CryptoCompress\Http\Curl\Request\Get,
		CryptoCompress\Http\Curl\Request\Post;

class Twitter {

	/**
	 * @var	array
	 */
	protected $config;

	/**
	 * @var	\CryptoCompress\Http\ITransport
	 */
	protected $transport;

	/**
	 * @var	\DOMDocument
	 */
	protected $document;

	/**
	 * @var	bool
	 */
	protected $cache;

	public function __construct(array $config, \CryptoCompress\Http\ITransport $transport, $cache = false) {
		$this->config		= $config;
		$this->transport	= $transport;
		$this->cache		= $cache;
	}

	/**
	 * @link	http://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
	 *
	 * @param	array	$get
	 *								count
	 *								since_id
	 *								max_id
	 *								page
	 *								trim_user
	 *								include_rts
	 *								include_entities
	 *								exclude_replies
	 *								contributor_details
	 *
	 * @return	Twitter\User\TimeLine
	 */
	public function timelineHome(array $get = array()) {
		$url = 'http://api.twitter.com/1.1/statuses/home_timeline.' . $this->config['format'];

		return $this->build(
			$this->transport->fetch(new Get($url, $get, array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\User\\TimeLine',
				'\\Twitter\\User\\TimeLine\\Twitt',
				'user' => '\\Twitter\\User',
			)
		);
	}

	/**
	 * @link	http://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
	 *
	 * @param	array	$get
	 *								user_id
	 *								screen_name
	 *								since_id
	 *								count
	 *								max_id
	 *								page
	 *								trim_user
	 *								include_rts
	 *								include_entities
	 *								exclude_replies
	 *								contributor_details
	 *
	 * @return	Twitter\User\TimeLine
	 */
	public function timelineUser(array $get = array()) {
		$url = 'http://api.twitter.com/1.1/statuses/user_timeline.' . $this->config['format'];

		return $this->build(
			$this->transport->fetch(new Get($url, $get, array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\User\\TimeLine',
				'\\Twitter\\User\\TimeLine\\Twitt',
				'user' => '\\Twitter\\User',
			)
		);
	}

	/**
	 * @link	http://dev.twitter.com/docs/api/1.1/get/friends/ids
	 *
	 * @param	array	$get
	 *								user_id
	 *								screen_name
	 *								cursor
	 *								stringify_ids
	 *
	 * @return
	 */
	public function friends(array $get = array('cursor' => -1)) {
		$url = 'http://api.twitter.com/1.1/friends/ids.' . $this->config['format'];

		return $this->build(
			$this->transport->fetch(new Get($url, $get, array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\Friends',
			)
		);
	}

	/**
	 * @link	http://dev.twitter.com/docs/api/1.1/get/friends/ids
	 *
	 * @param	array	$get
	 *								user_id
	 *								screen_name
	 *								include_entities
	 *
	 * @return
	 */
	public function destroyFriendships(array $get = array(), array $post = array()) {
		$url = 'http://api.twitter.com/1.1/friendships/destroy.' . $this->config['format'];

		return $this->build(
			$this->transport->fetch(new Post($url, $get, $post, array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\User',
				'status'	=> '\\Twitter\\User\\Status',
			)
		);
	}

	public function reply($text) {
		if (true === @$this->config['strict'] && strlen($text) > 140) {
			throw new Exception('Message too long!');
		} else {
			$text = substr($text, 0, 140);
		}

		$get = array();

		$url = 'http://api.twitter.com/1.1/statuses/update.' . $this->config['format'];

		return $this->build(
			$this->transport->fetch(new Post($url, $get, array('status' => $text), array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\User\\TimeLine\\Twitt',
				'user' => '\\Twitter\\User',
			)
		);
	}

	public function build($result, array $classNames = array()) {
		if (isset($result['error'])) {
			throw new Exception($result['error']);
		}

		if (isset($result['errors'])) {
			throw new Exception($result['errors'][0]['message'], $result['errors'][0]['code']);
		}
try {
		if (isset($result[0])) {
			$collection = array();
			foreach ($result as $entity) {
				$collection[] = $this->buildEntity($entity, $classNames, 1);
			}
			$nameCollection = __NAMESPACE__ . $classNames[0];
			return new $nameCollection($collection);
		}

		return $this->buildEntity($result, $classNames, 0);
} catch (\Exception $e) {
	w($result, $e);
	return $result;
}
	}

	public function buildEntity($entity, array $classNames = array(), $position = 1) {
		$nameEntity = __NAMESPACE__ . $classNames[$position];

		foreach ($classNames as $key => $className) {
			if (is_numeric($key) || !isset($entity[$key])) {
				continue;
			}
			$className = __NAMESPACE__ . $className;
			$entity[$key] = new $className($entity[$key]);
		}

		return new $nameEntity($entity);
	}
}
