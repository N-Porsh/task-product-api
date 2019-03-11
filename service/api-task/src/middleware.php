<?php

use Validator\JsonSchemaValidator\Validator;

$schema = $app->getContainer()->get('settings')['product-schema'];

$validation = function ($request, $response, $next) use ($schema) {
	$data = $request->getParsedBody();

	$validator = new Validator($schema, "#definitions/Product");
	$validator->validate(json_encode($data));
	if (!$validator->isValid()) {
		return $response->withJson(
			['status' => 'validation_failed', 'errors' => $validator->getErrors()],
			400);
	}

	$response = $next($request, $response);

	return $response;
};
