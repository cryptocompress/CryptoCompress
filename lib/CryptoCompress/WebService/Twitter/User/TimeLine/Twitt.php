<?php

namespace CryptoCompress\WebService\Twitter\User\TimeLine;

class Twitt {

	private $data;

	public function __construct(array $data = array())
	{
		$this->data = $data;
	}

	public function get($key)
	{
		return $this->data[$key];
	}

	public function timestamp()
	{
		return strtotime($this->data['created_at']);
	}

	public function id()
	{
		return $this->data['id_str'];
	}

	public function text()
	{
		return $this->data['text'];
	}

	public function hasTag($name)
	{
		foreach ($this->data['entities']['hashtags'] as $tag) {
			if ($tag['text'] == $name) {
				return true;
			}
		}

		return false;
	}

	public function names($exclude)
	{
		$exclude = (array)$exclude;

		$r = '@' . $this->data['user']['screen_name'];

		if (isset($this->data['entities']['user_mentions'])) {
			foreach ($this->data['entities']['user_mentions'] as $user) {
				if (!in_array($user['screen_name'], $exclude)) {
					$r .= ' @' . $user['screen_name'];
				}
			}
		}

		return $r;
	}

	public function user()
	{
		return new User($this->data['user']);
	}

	public function normalizeText()
	{
		$text	= $this->data['text'];

		$text	= preg_replace('/\@\p{L}+/u', '', $text);
		$text	= preg_replace('/\#\p{L}+/u', '', $text);
		$text	= preg_replace('/\p{Lu}+/u', ' \0', $text);
		#$text	= preg_replace('/\P{L}+/u', ' ', $text);

		return trim($text);
	}

	public function contains($key, $value = null)
	{
		if (is_array($key)) {
			$keysValues = $key;
		} else {
			$keysValues = array($key => $value);
		}

		foreach ($keysValues as $key => $keyValues) {
			$keyValues = (array)$keyValues;
			foreach ($keyValues as $value) {
				if (isset($this->data[$key]) && $this->data[$key] == $value) {
					return true;
				}
			}
		}

		return false;
	}
}
