<?php

namespace Validator\JsonSchemaValidator;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class RouteValidator
{
	public function __construct($jsonValidator)
	{
		$this->jsonValidator = $jsonValidator;
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
	{
		try {
			$this->jsonValidator->validate((string)$request->getBody());
		} catch (SchemaException $e) {
			$response->getBody()->write(json_encode(['message' => $e->getMessage(), 'hint' => $e->getHint()]));
			return $response->withStatus(400, 'Invalid JSON');
		}

		return $next($request, $response);
	}
}