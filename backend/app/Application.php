<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use Exception;
use Nette\Utils\AssertionException;
use Nette\Utils\Strings;
use Nette\Utils\Json;


final readonly class Application
{

	function __construct(private DIC $dic)
	{
	}



	function run(?Request $req): void
	{
		if (empty($req)) {
			$response = self::NotFoundResponse();
		}
		else {
			try {
				$routine = $this->lookupRoutineFor($req);
				// Preflight request
				if ($req->getMethod() === Request::MethodOptions) {
					$response = self::PreflightResponse();
				}
				else if ( ! $response = $routine->process($req)) {
					$response = self::NotFoundResponse();
				}
			}
			catch (RuntimeException|AssertionException $e) {
				$response = self::ClientErrorResponse($e);
			}
			catch (Exception $e) {
				$response = self::ServerErrorResponse($e);
			}
		}
		$this->sendHeaderCode($response->getCode());
		$this->sendHeaderContentType($response->getContentType());
		$this->sendHeaderCors('*');
		echo $response->render();
		exit;
	}



	private function lookupRoutineFor(Request $req): Routine
	{
		return $this->dic->getByClassName(self::formatRoutineClass($req));
	}



	function sendHeaderCode(int $code): void
	{
		$protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
		$reason = Response::ReasonPhrases[$code] ?? 'Unknown status';
		header("$protocol $code $reason");
	}



	function sendHeaderContentType(string $code): void
	{
		header("Content-Type: $code");
	}



	function sendHeaderCors(string $code): void
	{
		header("Access-Control-Allow-Origin: {$code}");
		header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
	}



	private static function formatRoutineClass(Request $req): string
	{
		return __namespace__ . '\\'
			. ucfirst($req->getResourceName())
			. 'Routine';
	}



	private static function NotFoundResponse(): Response
	{
		return new Response(Response::S404_NotFound, Response::ContentTypeJson, '');
	}



	private static function PreflightResponse(): Response
	{
		return new Response(Response::S200_OK, Response::ContentTypeJson, '');
	}



	private static function ClientErrorResponse(RuntimeException|AssertionException $e, int $code = Response::S400_BadRequest): Response
	{
		return new Response($code, Response::ContentTypeJson, Json::encode($e->getMessage()));
	}



	private static function ServerErrorResponse(Exception $e): Response
	{
		return new Response(Response::S500_InternalServerError, Response::ContentTypeJson, Json::encode($e->getMessage()));
	}

}
