<?php
namespace Tests;

use Validator\JsonSchemaValidator\UriRetriever;

class UriRetrieverTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowExceptionIfFileIsNotFound()
    {
        $retriver = new UriRetriever(__DIR__);

        $this->expectException(\ErrorException::class);

        $retriver->retrieve('non-existing.json');
    }

    public function testReturnDecodedJson()
    {
        $retriver = new UriRetriever(__DIR__);

        $decodedJson = $retriver->retrieve('test-schema.json');

        $this->assertInstanceOf('\stdClass', $decodedJson);
    }
}