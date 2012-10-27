<?php

/**
 * @link	https://dev.twitter.com/docs/error-codes-responses
 */

namespace CryptoCompress\WebService\Twitter;

class Exception extends \CryptoCompress\WebService\Exception {
	const NOT_FOUND			= 34;	// Sorry, that page does not exist	Corresponds with an HTTP 404 - the specified resource was not found.
	const OVER_CAPACITY		= 130;	// Over capacity	- Corresponds with an HTTP 503 - Twitter is temporarily over capacity.
	const INTERNAL_ERROR	= 131;	// Internal error	- Corresponds with an HTTP 500 - An unknown internal error occurred.
}

/*
200	OK	Success!
304	Not Modified	There was no new data to return.
400	Bad Request	The request was invalid. An accompanying error message will explain why. This is the status code will be returned during rate limiting.
401	Unauthorized	Authentication credentials were missing or incorrect.
403	Forbidden	 The request is understood, but it has been refused. An accompanying error message will explain why. This code is used when requests are being denied due to update limits.
404	Not Found	The URI requested is invalid or the resource requested, such as a user, does not exists.
406	Not Acceptable	Returned by the Search API when an invalid format is specified in the request.
420	Enhance Your Calm	Returned by the Search and Trends API when you are being rate limited.
500	Internal Server Error	Something is broken. Please post to the group so the Twitter team can investigate.
502	Bad Gateway	Twitter is down or being upgraded.
503	Service Unavailable	The Twitter servers are up, but overloaded with requests. Try again later.
504	Gateway timeout	The Twitter servers are up, but the request couldn't be serviced due to some failure within our stack. Try again later.
 */