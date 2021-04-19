<?php

return [

	/**
	 * Configure client constructor parameters. Example: base_uri
	 */
	'defaults' => [
		/**
		 * Configures a base URL for the client so that requests created using a relative URL are combined with the base_url
		 * See: http://guzzle.readthedocs.org/en/latest/quickstart.html#creating-a-client
		 */
		'base_uri' => 'http://192.168.0.39:21465',

		/**
		 * Secret Key
		 * See: https://github.com/wppconnect-team/wppconnect-server#secret-key
		 */
		'secret_key' => 'MYKeYPHP'
	]

];
