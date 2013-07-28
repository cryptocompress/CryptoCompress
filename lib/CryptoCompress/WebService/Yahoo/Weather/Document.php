<?php

namespace CryptoCompress\WebService\Yahoo\Weather;

class Document /* implements \CryptoCompress\Interfaces\WeatherDocument */ {

	/**
	 * @var	\DOMDocument
	 */
	protected $document;

	public function __construct(\DOMDocument $document) {
		$this->document = $document;
	}

	public function __destruct() {
		$this->document = null;
	}

	/**
	 * @return	string
	 */
	public function __toString() {
		return $this->document->saveXML();
	}

	protected function parse() {
		if (empty($this->data)) {
			$this->data = array();

			$xpath = new \DOMXPath($this->document);
			$nodes = $xpath->query('//yweather:*');
			foreach ($nodes as $node) {
				list (, $name) = explode(':', $node->getNodePath());

				foreach ($node->attributes as $attribute) {
					$this->data[$name][$attribute->name] = $attribute->value;
				}
			}
		}

		return $this->data;
	}

	/**
	 * @return	array
	 */
	public function current() {
		$this->parse();

		return array(
			'temp'	=> $this->data['condition']['temp'],
			'text'	=> $this->translate($this->data['condition']['code'], $this->data['condition']['text']),
		);
	}

	/**
	 * @return	array
	 */
	public function forecast($day = 0) {
		$this->parse();

		return array(
			'low'	=> $this->data['forecast[' . ($day + 1) . ']']['low'],
			'high'	=> $this->data['forecast[' . ($day + 1) . ']']['high'],
			'text'	=> $this->translate($this->data['forecast[' . ($day + 1) . ']']['code'], $this->data['forecast[' . ($day + 1) . ']']['text']),
		);
	}

	public function atmosphere($key = null) {
		$this->parse();

		if (isset($key)) {
			return $this->data['atmosphere'][$key];
		}

		return $this->data['atmosphere'];
	}

	protected function translate($code, $text) {
		return \CryptoCompress\WebService\Yahoo\Weather\ConditionCodes\German::get($code, $text);
	}

}
