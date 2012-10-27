<?php

namespace CryptoCompress\WebService\Twitter;

class User {

	private $data;

	public function __construct(array $data = array())
	{
		$this->data = $data;
	}

	public function id()
	{
		return $this->data['id_str'];
	}

	public function name()
	{
		return $this->data['screen_name'];
	}
}
