<?php

namespace CryptoCompress\Http;

interface ITransport {
	public function fetch(IRequest $request);

	public function fetchMany(array $request);

	public function get($url);

	public function getDocument($url);
}