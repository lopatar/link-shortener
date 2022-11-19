<?php
declare(strict_types=1);

namespace App\Models;

use Sdk\Config;
use Sdk\Database\MariaDB\Connection;
use Sdk\Http\Request;
use Sdk\Http\Response;
use Sdk\Utils\Random;

final class ShortenedLink
{
	public function __construct(public readonly string $code, public readonly string $link) {}

	public static function exists(string $code): bool
	{
		return Connection::query('SELECT code FROM links WHERE code=?', [$code])->num_rows === 1;
	}

	public static function fromCode(string $code): ?self
	{
		if (!self::exists($code)) {
			return null;
		}

		$query = Connection::query('SELECT link FROM links WHERE code=?', [$code]);
		$data = $query->fetch_assoc();

		return new self($code, $data['link']);
	}

	public static function insert(string $url, ?string $code = null): ?self
	{
		if ($code !== null && self::exists($code)) {
			return null;
		}

		if ($code === null)
		{
			do {
				$code = Random::stringSafe(config::SHORTENED_URL_CODE_LENGTH);
			} while (self::exists($code));
		}

		Connection::query('INSERT INTO links(code, link) VALUES(?,?)', [$code, $url]);
		return new self($code, $url);
	}

	public function redirect(Response $response): never
	{
		$response->addHeader('Location', $this->link);
		$response->send();
	}

	public function getPublicLink(Request $request): string
	{
		$url = $request->getUrl();
		$domainName = $url->domainName->fullText;
		return "$url->scheme://$domainName/$this->code";
	}
}