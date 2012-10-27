<?php

namespace CryptoCompress\Http;

interface ITransport {
	public function fetch(IRequest $request);

	public function fetchMany(array $request);
}