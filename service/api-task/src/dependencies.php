<?php
$container = $app->getContainer();

//Global error handler
$container['errorHandler'] = function ($c) {
	return function ($request, $response, $exception) use ($c) {
		return $response->withJson([
			'status' => 'Something went wrong! Internal server Error',
			'error' => $exception->getMessage()
		], 500);
	};
};

//Override the default Not Found Handler after App
unset($container['notFoundHandler']);
$container['notFoundHandler'] = function ($c) {
	return function ($request, $response) use ($c) {
		$response = new \Slim\Http\Response(404);
		return $response->withJson(['error' => 'Incorrect route'], 404);
	};
};

$container['db'] = function ($c) use ($container) {
	return $container->get('settings')['db'];
};

$container['logger'] = function ($c) {
	$settings = $c->get('settings')['logger'];
	$logger = new Monolog\Logger($settings['name']);
	$logger->pushProcessor(new Monolog\Processor\UidProcessor());
	$logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
	return $logger;
};
