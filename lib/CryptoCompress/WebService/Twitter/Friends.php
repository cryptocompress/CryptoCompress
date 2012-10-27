<?php

namespace CryptoCompress\WebService\Twitter;

class Friends {

	private $data;

	public function __construct(array $data = array())
	{
		$this->data = $data;
	}

	public function getIds()
	{
		return $this->data['ids'];
	}

}
