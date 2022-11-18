<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShortenedLink;
use Sdk\Http\Request;
use Sdk\Http\Response;

final class Home
{
	public static function redirect(Request $request, Response $response, array $args): Response
	{
		$code = $args['code'];
		$shortenedLink = ShortenedLink::fromCode($code);

		$shortenedLink?->redirect($response);

		$response->addHeader('Location', '/');
		return $response;
	}
}