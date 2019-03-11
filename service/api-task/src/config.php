<?php
return [
	'settings' => [
		'mode' => true,
		'displayErrorDetails' => true, // set to false in production
		'addContentLengthHeader' => false, // Allow the web server to send the content-length header

		// Database settings
		'db' => [
			'db_host' => 'localhost',
			'db_port' => '',
			'db_name' => 'task_db',
			'db_user' => 'root',
			'db_pass' => '12345678'
		],

		// Monolog settings
		'logger' => [
			'name' => 'API Task',
			'level' => Monolog\Logger::DEBUG,
			'path' => __DIR__ . '/../logs/app.log',
		],

		'product-schema' => __DIR__ . '/../public/product-schema.json'
	],
];