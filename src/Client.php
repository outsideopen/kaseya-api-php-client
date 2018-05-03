<?php

/**
 * Copyright 2010 Google Inc.
 * Copyright 2018 Outside Open, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Kaseya;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\Psr7\Request;
use Kaseya\Auth\Credentials;
use Kaseya\Auth\Token;
use Kaseya\Http\REST;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler as MonologStreamHandler;
use Monolog\Handler\SyslogHandler as MonologSyslogHandler;

class Client
{
	const API_BASE_PATH = '/api/v1.0';

	/**
	 * The Kaseya REST API host
	 *
	 * @var string
	 */
	private $host;

	/**
	 * The credentials for the Kaseya REST API
	 *
	 * @var Credentials
	 */
	private $auth;

	/**
	 * The HTTP Client
	 *
	 * @var ClientInterface
	 */
	private $http;

	/**
	 * The Kaseya REST API Token
	 *
	 * @var Token
	 */
	private $token;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct($host, $user, $password)
	{
		$this->host = $host;
		$this->auth = new Credentials($user, $password);
	}

	/**
	 * Returns the root url for the Kaseya API
	 *
	 * @return string
	 */
	public function rootUrl()
	{
		return "https://{$this->host}" . self::API_BASE_PATH;
	}

	/**
	 * Helper method to execute deferred HTTP requests.
	 *
	 * @param $request \Psr\Http\Message\RequestInterface
	 * @throws \RuntimeException
	 * @return object of the type of the expected class or Psr\Http\Message\ResponseInterface.
	 */
	public function execute(RequestInterface $request, $expectedClass = null)
	{
		$http    = $this->authorize();
		$request = $request->withHeader('User-Agent', 'kaseya-php-sdk/alpha');
		$request = $request->withHeader('Authorization', "Bearer {$this->token}");

		return REST::execute($http, $request, $expectedClass);
	}

	public function authorize(ClientInterface $http = null)
	{
		if (!$http) {
			$http = $this->getHttpClient();
		}

		if (isset($this->token)) {
			return $http;
		}

		// fetch the access token
		$request     = new Request(
			'GET',
			$this->rootUrl() . '/auth',
			[
				'content-type'  => 'application/json',
				'authorization' => "Basic {$this->auth->hash()}"
			]
		);
		$response    = REST::execute($http, $request, Token::class);
		$this->token = $response->Token;

		return $http;
	}

	public function getAccessToken()
	{
		return $this->token;
	}

	/**
	 * @return ClientInterface implementation
	 */
	public function getHttpClient()
	{
		if (null === $this->http) {
			$this->http = $this->createDefaultHttpClient();
		}

		return $this->http;
	}

	protected function createDefaultHttpClient()
	{
		$options = [
			'exceptions' => false,
			'base_uri'   => self::API_BASE_PATH
		];

		return new GuzzleClient($options);
	}

	/**
	 * Set the Logger object
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * @return LoggerInterface implementation
	 */
	public function logger()
	{
		if (!isset($this->logger)) {
			$this->logger = $this->createDefaultLogger();
		}

		return $this->logger;
	}

	protected function createDefaultLogger()
	{
		$logger  = new Logger('kaseya-api-php-client');
		$handler = new MonologStreamHandler('php://stderr', Logger::NOTICE);
		$logger->pushHandler($handler);

		return $logger;
	}
}