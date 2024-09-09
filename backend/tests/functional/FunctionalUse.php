<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use GuzzleHttp\Client;
use Nette\Utils\Json;
use Nette\Utils\FileSystem;


trait FunctionalUse
{

	function setUp(): void {
		// resetovat databázi
		FileSystem::copy(__dir__ . '/../../data.sqlite3-example', __dir__ . '/../../../data/data.sqlite3', True);
	}



	private function fetchGet(string $url) {
		$url = rtrim(getenv('BACKEND_URL'), '/') . $url;
		$client = new Client();
		return $client->request('GET', $url, [
			'http_errors' => False,
		]);
	}



	private function fetchPush(string $url, $data) {
		$url = rtrim(getenv('BACKEND_URL'), '/') . $url;
		$client = new Client();
		return $client->request('POST', $url, [
			'json' => $data,
			'http_errors' => False,
		]);
	}



	private function fetchPut(string $url, $data) {
		$url = rtrim(getenv('BACKEND_URL'), '/') . $url;
		$client = new Client();
		return $client->request('PUT', $url, [
			'json' => $data,
			'http_errors' => False,
		]);
	}



	private function fetchDelete(string $url) {
		$url = rtrim(getenv('BACKEND_URL'), '/') . $url;
		$client = new Client();
		return $client->request('DELETE', $url);
	}



	private function assertStatusCode(int $code, $response): void
	{
		$this->assertEquals($code, $response->getStatusCode());
	}



	/**
	 * @param array|object|string $expected
	 */
	private function assertContent($expected, $response): void
	{
		$this->assertEquals($expected
			, Json::decode($response->getBody()));
	}

}
