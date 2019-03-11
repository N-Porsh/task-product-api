<?php
$app->group('/api/v1', function (\Slim\App $app) use ($validation) {

	/**
	 * add new product
	 */
	$app->post('/products', function ($request, $response) {
		$data = $request->getParsedBody();


		$product = new \Models\Product();
		$this->logger->info("New product", $data);
		$product->addProduct($data['name'], $data['price']);

		return $response->withStatus(201);
	})->add($validation);

	/**
	 * Update specific product by id
	 */
	$app->put('/products/{id}', function ($request, $response) {
		$id = $request->getAttribute('id');
		$data = $request->getParsedBody();

		$product = new \Models\Product();
		$this->logger->info("Updating product by ID", ['id' => $id, 'data' => $data]);
		$res = $product->updateProduct($id, $data['name'], $data['price']);
		if (!$res) {
			$this->logger->info("Could not find/update product", ['id' => $id]);
			return $response->withJson(['code' => 404, 'message' => 'Product not found'], 404);
		}

		return $response->withStatus(200);
	})->add($validation);

	/**
	 * Delete product by id
	 */
	$app->delete('/products/{id}', function ($request, $response) {
		$id = $request->getAttribute('id');

		$product = new \Models\Product();
		$this->logger->info("Deleting product by ID", ['id' => $id]);
		$res = $product->deleteProduct($id);
		if (!$res) {
			$this->logger->info("Could not find/delete product", ['id' => $id]);
			return $response->withJson(['code' => 404, 'message' => 'Product not found'], 404);
		}
		return $response->withStatus(200);
	});

	/**
	 * delete all products
	 */
	$app->delete('/products', function ($request, $response) {
		$product = new \Models\Product();
		$this->logger->info("Deleting all products");
		$product->deleteAllProducts();

		return $response->withStatus(200);
	});

	/**
	 * get All products
	 */
	$app->get('/products', function ($request, $response) {
		$this->logger->info("Getting all products");
		$product = new \Models\Product();
		$data = $product->getProducts();

		return $response->withJson($data, 200);
	});

	/**
	 * get product by Name
	 */
	$app->get('/products/{name}', function ($request, $response) {
		$name = $request->getAttribute('name');
		$this->logger->info("Getting product by name", ['name' => $name]);

		$product = new \Models\Product();
		$data = $product->getProductByName($name);
		if (!$data) {
			$this->logger->info("Product not found", ['name' => $name]);
			return $response->withJson(['code' => 404, 'message' => 'Product not found'], 404);
		}

		return $response->withJson($data, 200);
	});


});
