<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Config;
use Sdk\App;

$app = new App(new Config());

$app->view('/', 'Home.html');

$app->get('/{code}', 'Home::redirect')
	->whereParam('code')
	?->setLimit(2, 32);

$app->get('/api/is-taken/{code}', 'Api::taken')
	->whereParam('code')
	->setLimit(2, 32);

$app->post('/api/shorten', 'Api::shorten');

$app->run();