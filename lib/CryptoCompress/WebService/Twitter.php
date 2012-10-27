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

	public function __construct(array $config, \CryptoCompress\Http\ITransport $transport, $cache = false)
	{
		$this->config		= $config;
		$this->transport	= $transport;
		$this->cache		= $cache;
    }

	/**
	 * @link	http://dev.twitter.com/docs/api/1/get/statuses/user_timeline
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
		$url = 'http://api.twitter.com/1/statuses/home_timeline.' . $this->config['format'];

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
	 * @link	http://dev.twitter.com/docs/api/1/get/statuses/user_timeline
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
		$url = 'http://api.twitter.com/1/statuses/user_timeline.' . $this->config['format'];

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
	 * @link	http://dev.twitter.com/docs/api/1/get/friends/ids
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
		$url = 'http://api.twitter.com/1/friends/ids.' . $this->config['format'];

		return $this->build(
            $this->transport->fetch(new Get($url, $get, array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\Friends',
			)
		);
	}

	/**
	 * @link	http://dev.twitter.com/docs/api/1/get/friends/ids
	 *
	 * @param	array	$get
	 *								user_id
	 *								screen_name
	 *								include_entities
	 *
	 * @return
	 */
	public function destroyFriendships(array $get = array(), array $post = array()) {
		$url = 'http://api.twitter.com/1/friendships/destroy.' . $this->config['format'];

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

		$url = 'http://api.twitter.com/1/statuses/update.' . $this->config['format'];

		return $this->build(
            $this->transport->fetch(new Post($url, $get, array('status' => $text), array('oauth' => $this->config['oauth'])))->document(),
			array(
				'\\Twitter\\User\\TimeLine\\Twitt',
				'user' => '\\Twitter\\User',
			)
		);
	}

	protected function errorToException(array $result) {

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


//	public function call($url, $method = 'GET', array $params = array(), $data) {
//		$url .= '.' . $this->config['format'] . '?' . http_build_query($params);
//
//		return $this->transport->call($method, $url, $data, array('oauth' => $this->config['oauth']));
//	}
//











//
//
//
//
//
//
//	public function search($query, $page = 0)
//	{
//		$url	= 'http://search.twitter.com/search';
//		$method	= 'GET';
//
//		$params = array();
//		$params['q']				= $query;
//		$params['result_type']		= 'recent';
//		$params['rpp']				= 100;
//		$params['include_entities']	= true;
//		$params['page']				= $page;
//
//		if (!empty($name)) {
//			# since_id
//
//			#?q=blue%20angels
//			//rpp=5
//			//include_entities=true
//			//result_type=mixed
//		}
//
//		return new Entity\SearchLine($this->call($url, $method, $params));
//	}
//
//	public function geoSearch($name = '')
//	{
//		$url	= 'http://api.twitter.com/1/geo/search'; #.json?query=Toronto';
//		$method	= 'GET';
//
//		$params = array();
//
//		if (!empty($name)) {
//			$params['query']		= $name;
//			$params['granularity']	= 'city';
//			$params['max_results']	= 1;
//		}
//
//		return $this->call($url, $method, $params);
//	}
//
//	public function mentions($sinceId = '')
//	{
//		$url	= 'http://api.twitter.com/1/statuses/mentions';
//		$method	= 'GET';
//
//		$params = array();
//
//		if (!empty($sinceId)) {
//			$params = array('since_id' => $sinceId);
//		}
//
//		$params['include_entities'] = true;
//
//		return new Entity\TimeLine($this->call($url, $method, $params));
//	}
//
//	public function reply($text, \CryptoCompress\DataAccess\Twitter\Entity\TimeLine\Twitt $prevTwitt = null, \CryptoCompress\DataAccess\Google\Maps\Entity\Address $address = null) {
//		$params = array();
//
//		if (isset($prevTwitt)) {
//			$params['in_reply_to_status_id'] = $prevTwitt->id();
//		}
//
//		if (isset($address)) {
//			list ($lat, $lng) = $address->coordinates();
//
//			$params['lat']					= $lat;
//			$params['long']					= $lng;
//			$params['display_coordinates']	= true;
//			//'place_Id'				=> $geo['result']['places'][0]['id'],
//		}
//
//		$url	= 'http://api.twitter.com/1/statuses/update';
//		$method	= 'POST';
//
//		return $this->call($url, $method, $params, $text);
//	}
//
//	public function follow(\CryptoCompress\DataAccess\Twitter\Entity\TimeLine\User $user) {
//
//		$url	= 'http://api.twitter.com/1/friendships/create';
//		$method	= 'POST';
//
//		return $this->call($url, $method, array('user_id' => $user->id()));
//	}
//
//	public function create($text = '') {
//
//		$url	= 'http://api.twitter.com/1/statuses/update';
//		$method	= 'POST';
//
//		return $this->call($url, $method);
//	}
//
//	public function call($url, $method = 'GET', array $urlParams = array(), $data = '') {
//
//		$data = substr($data, 0, 140);
//
//		$url .= '.' . $this->config['format'];
//
//		$timestamp	= time();
//		$nonce		= md5(microtime());
//
//		$authParams = array(
//			'oauth_consumer_key'		=> $this->config['oauth']['consumer_key'],
//			'oauth_signature_method'	=> $this->config['oauth']['signature_method'],
//			'oauth_token'				=> $this->config['oauth']['access_token'],
//			'oauth_version'				=> $this->config['oauth']['version'],
//			'oauth_nonce'				=> $nonce,
//			'oauth_timestamp'			=> $timestamp,
//		);
//
//		$arrSig = array_merge($urlParams, $authParams);
//
//		$content = null;
//		if (!empty($data)) {
//			$arrSig['status'] = $data;
//			$content = 'status=' . rawurlencode($data);
//		}
//
//		$encSig = array();
//		foreach ($arrSig as $k => $v) {
//			$encSig[rawurlencode($k)] = rawurlencode($v);
//		}
//
//		ksort($encSig, SORT_STRING);
//
//		$sig = '';
//		foreach ($encSig as $k => $v) {
//			$sig .= $k . '=' . $v . '&';
//		}
//
//		$sig		= strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(rtrim($sig, '&'));
//		$signingKey	= rawurlencode($this->config['oauth']['consumer_secret']) . '&' . rawurlencode($this->config['oauth']['access_token_secret']);
//
//		$urlParamsString = (empty($urlParams)) ? '' : '?' . http_build_query($urlParams);
//
//		$header	= array(
//			'Content-Type'	=> 'application/x-www-form-urlencoded',
//			'Authorization'	=> $this->buildAuth($timestamp, $nonce, $sig, $signingKey)
//		);
//
//		$r = $this->plainHttpCall($url . $urlParamsString, $method, $header, $content);
//
//		if ($this->config['format'] == 'json') {
//			return json_decode($r, true);
//		} else {
//			$xml = new \DOMDocument(1, 'UTF-8');
//			$xml->preserveWhiteSpace  = false;
//			$xml->formatOutput        = true;
//			$xml->strictErrorChecking = false;
//			$xml->substituteEntities  = true;
//			$xml->validateOnParse     = false;
//			$xml->loadXML($r);
//
//			return $xml;
//		}
//	}
//
//	protected function plainHttpCall($url, $method = 'GET', array $headers = array(), $content = null)
//	{
//		if (isset($this->transport)) {
//			return $this->transport->plainHttpCall($url, $method, $headers, $content);
//		}
//d('kein transport da!');
//		$cacheKey = './cache/' . md5($method . $url . $content) . '.cache';
//		if ($this->cache && false !== realpath($cacheKey)) {
//			return file_get_contents($cacheKey);
//		}
//
//		$header = '';
//		foreach ($headers as $k => $v) {
//			$header .= trim($k) . ': ' . trim($v) . "\r\n";
//		}
//
//		$ret = file_get_contents($url, false, stream_context_create(array('http' => array(
//			'method'	=> $method,
//			'header'	=> $header,
//			'content'	=> $content,
//		))));
//
//		if ($this->cache) {
//			file_put_contents($cacheKey, $ret);
//		}
//
//		return $ret;
//	}

}