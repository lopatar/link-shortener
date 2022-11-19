<?php
declare(strict_types=1);

namespace App;

use App\Models\ShortenedLink;

abstract class Config
{
	/**
	 * Minimal value is 2, max is 32
	 * @see ShortenedLink::insert()
	 */
	const SHORTENED_URL_CODE_LENGTH = 8;
}