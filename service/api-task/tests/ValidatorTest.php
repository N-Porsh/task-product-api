<?php

namespace Tests;

use Validator\JsonSchemaValidator\SchemaException;
use Validator\JsonSchemaValidator\Validator;


class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	public function testValidRequiredProperties()
	{
		$input = '{"test":"lol"}';

		$validator = new Validator(__DIR__ . '/test-schema.json');
		$validator->validate($input);

		$this->assertTrue($validator->isValid());
	}

	public function testWeDoNotThrowOnValidJson()
	{
		$input = '{"test":"lol"}';
		$validator = new Validator(__DIR__ . '/test-schema.json');

		$validator->validate($input);

		$this->assertTrue($validator->isValid());
	}

	public function testWeDoNotThrowOnValidUtf8String()
	{
		$input = "{\"test\": \"\xe2\x98\x81\"}";
		$validator = new Validator(__DIR__ . '/test-schema.json');
		$validator->validate($input);
		$this->assertTrue($validator->isValid());
	}


	public function testWeThrowOnInvalidInput()
	{
		$input = 'weoij0923r';
		$validator = new Validator(__DIR__ . '/test-schema.json');

		$this->expectException(SchemaException::class);
		$this->expectExceptionMessage('Input could not be parsed as json');

		$validator->validate($input);
	}

	public function testWeThrowOnSchemaNotFound()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Schema file could not be found');

		$validator = new Validator("oijwefoijwefoijwef");
	}

	public function testWeThrowOnInvalidSchema()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Schema file could not be parsed as JSON');

		$validator = new Validator(__DIR__ . '/test-invalid-schema.json');
	}
}