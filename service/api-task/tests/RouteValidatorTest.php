<?php
namespace Tests;

use Validator\JsonSchemaValidator\RouteValidator;
use Validator\JsonSchemaValidator\SchemaException;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

class RouteValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $request;
    private $response;

    public function setUp()
    {
        $uri = Uri::createFromString('https://example.com/foo/bar');
        $headers = new Headers();
        $cookies = [];
        $env = Environment::mock();
        $serverParams = $env->all();
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write('{"json":"data"}');
        $this->request = new Request('POST', $uri, $headers, $cookies, $serverParams, $body);
        $this->response = new Response();
    }

    public function testValidationIsDoneOnBodyOfRequest()
    {
        $jsonValidator = $this->getMockBuilder('\Jach\Json\Validator')
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();
        $jsonValidator->expects($this->once())
            ->method('validate')
            ->with('{"json":"data"}');

        $routevalidator = new RouteValidator($jsonValidator);
        $wasCalled = false;
        $routevalidator($this->request, $this->response, function ($request, $response) use (&$wasCalled) {
            $wasCalled = true;
            return $response;
        });

        $this->assertTrue($wasCalled);
    }

    public function testReturn400ResponseIfValidationFailed()
    {
        $jsonValidator = $this->getMockBuilder('\Jach\Json\Validator')
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();
        $jsonValidator->expects($this->once())
            ->method('validate')
            ->with('{"json":"data"}')
            ->will($this->throwException(new SchemaException('JSON did not validate', 'A hint')));


        $routevalidator = new RouteValidator($jsonValidator);
        $wasCalled = false;
        $response = $routevalidator($this->request, $this->response, function ($request, $response) use (&$wasCalled) {
            $wasCalled = true;
            return $response;
        });

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"message":"JSON did not validate","hint":"A hint"}', $response->getBody());
        $this->assertFalse($wasCalled);
    }
}