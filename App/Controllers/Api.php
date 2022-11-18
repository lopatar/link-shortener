<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShortenedLink;
use Sdk\Http\Entities\StatusCode;
use Sdk\Http\Request;
use Sdk\Http\Response;

final class Api
{
	public static function shorten(Request $request, Response $response, array $args): Response
	{
		$code = $request->getPost('code');

		if ($code === '') //The parameter is sent to server anyways, but as an empty string!
		{
			$code = null;
		}

		if ($code !== null && !self::validateCode($code)) {
			$response->write("$code does not meet the constraints of being >= 2 && >= 32 characters long!");
			$response->setStatusCode(StatusCode::BAD_REQUEST);
			return $response;
		}

		$link = $request->getPost('link');

		if (!filter_var($link, FILTER_VALIDATE_URL)) {
			$response->write("$link is not a valid URL!");
			$response->setStatusCode(StatusCode::BAD_REQUEST);
			return $response;
		}

		$shortenedLink = ShortenedLink::insert($link, $request->getRoute()->config, $code);
		$response->write(($shortenedLink == null) ? "$code already exists!" : $shortenedLink->getPublicLink($request));
		return $response;
	}

	public static function taken(Request $request, Response $response, array $args): Response
	{
		$code = $args['code'];
		$json = [
			'taken' => ShortenedLink::exists($code)
		];

		$response->addHeader('Content-Type', 'application/json');
		$response->write(json_encode($json));
		return $response;
	}

	private static function validateCode(string $code): bool
	{
		$length = strlen($code);
		return $length >= 2 && $length <= 32;
	}
}