<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use Nette\Utils\Strings;


final class Request
{
	/** HTTP request method */
	const
		MethodGet = 'GET',
		MethodPost = 'POST',
		MethodHead = 'HEAD',
		MethodPut = 'PUT',
		MethodDelete = 'DELETE',
		MethodPatch = 'PATCH',
		MethodOptions = 'OPTIONS';


	/**
	 * Use nette/http for example
	 */
	static function FromGlobals(): ?self
	{
		$requestUrl = $_SERVER['REQUEST_URI'] ?? '/';

		list($path, $query) = strpos($requestUrl, '?')
			? explode('?', $requestUrl, 2)
			: [$requestUrl, Null];
		$query = $query
			? self::http_parse_query($query)
			: [];

		if ($parts = Strings::match($path, '~^/api/([^/]+)/?(.*)~')) {
			return new static(self::resolveMethod(), $parts[1], $parts[2], $query
				, fn(): string => (string) file_get_contents('php://input')
				);
		}

		return Null;
	}



	private static function resolveMethod(): string
	{
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
		if (
			$method === 'POST'
			&& preg_match('#^[A-Z]+$#D', $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? '')
		) {
			$method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
		}

		return $method;
	}


	/** @var ?callable */
	private $rawBodyCallback;


	function __construct(private string $method, private string $resource, private ?string $selector, private array $query, ?callable $rawBodyCallback = null)
	{
		$this->rawBodyCallback = $rawBodyCallback;
	}



	function getMethod(): string
	{
		return $this->method;
	}



	function getResourceName(): string
	{
		return $this->resource;
	}



	function getSelector(): ?string
	{
		return $this->selector;
	}



	/**
	 * Returns raw content of HTTP request body.
	 */
	function getRawBody(): ?string
	{
		return $this->rawBodyCallback ? ($this->rawBodyCallback)() : null;
	}



	/**
	 * @return array<string, string>
	 */
	private static function http_parse_query(string $src): array
	{
		$dest = [];
		foreach (explode('&', $src) as $x) {
			$parts = explode('=', $x, 2);
			$dest[$parts[0]] = $parts[1] ?? Null;
		}
		return $dest;
	}

}
