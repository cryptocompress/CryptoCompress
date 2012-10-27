<?php

namespace CryptoCompress\WebService\Twitter\User;

class TimeLine implements \Countable, \IteratorAggregate {

	private $collection;

	public function __construct(array $collection = array()) {
		$this->collection = $collection;
	}

	public function count() {
		return count($this->collection);
	}

	public function getIterator() {
		return new \ArrayIterator($this->collection);
	}

	public function first() {
		reset($this->collection);

		return current($this->collection);
	}

	public function isEmpty()
	{
		return empty($this->collection);
	}
}
