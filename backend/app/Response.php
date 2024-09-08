<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Todo;

use RuntimeException;
use LogicException;
use Nette\Utils\Strings;


final readonly class Response
{

	/** HTTP 1.1 response code */
	const
		S100_Continue = 100,
		S101_SwitchingProtocols = 101,
		S102_Processing = 102,
		S200_OK = 200,
		S201_Created = 201,
		S202_Accepted = 202,
		S203_NonAuthoritativeInformation = 203,
		S204_NoContent = 204,
		S205_ResetContent = 205,
		S206_PartialContent = 206,
		S207_MultiStatus = 207,
		S208_AlreadyReported = 208,
		S226_ImUsed = 226,
		S300_MultipleChoices = 300,
		S301_MovedPermanently = 301,
		S302_Found = 302,
		S303_PostGet = 303,
		S304_NotModified = 304,
		S305_UseProxy = 305,
		S307_TemporaryRedirect = 307,
		S308_PermanentRedirect = 308,
		S400_BadRequest = 400,
		S401_Unauthorized = 401,
		S402_PaymentRequired = 402,
		S403_Forbidden = 403,
		S404_NotFound = 404,
		S405_MethodNotAllowed = 405,
		S406_NotAcceptable = 406,
		S407_ProxyAuthenticationRequired = 407,
		S408_RequestTimeout = 408,
		S409_Conflict = 409,
		S410_Gone = 410,
		S411_LengthRequired = 411,
		S412_PreconditionFailed = 412,
		S413_RequestEntityTooLarge = 413,
		S414_RequestUriTooLong = 414,
		S415_UnsupportedMediaType = 415,
		S416_RequestedRangeNotSatisfiable = 416,
		S417_ExpectationFailed = 417,
		S421_MisdirectedRequest = 421,
		S422_UnprocessableEntity = 422,
		S423_Locked = 423,
		S424_FailedDependency = 424,
		S426_UpgradeRequired = 426,
		S428_PreconditionRequired = 428,
		S429_TooManyRequests = 429,
		S431_RequestHeaderFieldsTooLarge = 431,
		S451_UnavailableForLegalReasons = 451,
		S500_InternalServerError = 500,
		S501_NotImplemented = 501,
		S502_BadGateway = 502,
		S503_ServiceUnavailable = 503,
		S504_GatewayTimeout = 504,
		S505_HttpVersionNotSupported = 505,
		S506_VariantAlsoNegotiates = 506,
		S507_InsufficientStorage = 507,
		S508_LoopDetected = 508,
		S510_NotExtended = 510,
		S511_NetworkAuthenticationRequired = 511;

	const ReasonPhrases = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	];

	const ContentTypeJson = 'application/json; charset=utf-8';


	function __construct(private int $code, private string $contentType, private string $content)
	{
	}



	function render(): string
	{
		return $this->content;
	}



	function getCode(): int
	{
		return $this->code;
	}



	function getContentType(): string
	{
		return $this->contentType;
	}

}
