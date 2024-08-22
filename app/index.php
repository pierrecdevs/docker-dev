<?php

// Our middewares
use Middlewares\ContentType;
use Middlewares\ErrorFormatter\JsonFormatter;
use Middlewares\ErrorHandler;
use Middlewares\FastRoute as FastRouteMiddleware;
use Middlewares\JsonPayload;
use Middlewares\RequestHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
//use Relay\Relay;
require_once __DIR__ . '/vendor/autoload.php';

$routes = require(__DIR__ . '/config/routes.php');

$simpleDispatcher = \FastRoute\simpleDispatcher($routes); 

$middlewareStack = [
  new ErrorHandler([new JsonFormatter()]),
  new JsonPayload(),
  new ContentType(['json']),
  new FastRouteMiddleware($simpleDispatcher),
  (new RequestHandler())->handlerAttribute('route'),
];


$dispatcher = new Dispatcher($middlewareStack);
$request =\GuzzleHttp\Psr7\ServerRequest::fromGlobals();
$response = $dispatcher->dispatch($request);

http_response_code($response->getHeaders());
foreach ($response->getHeaders() as $key => $value) {
  header("{$key}: {$value}");
}

switch($response->getStatusCode())
{
  case 404:
    $response = Factory::createResponse(404, $response->getReasonPhrase());
    $response->getBody()->write(string: json_encode(['message' => 'Route not found']));
  break;
  
  case 405:
    $response = Factory::createResponse(405, $response->getReasonPhrase());
    $response->getBody()->write(string: json_encode(['message' => 'Method not allowed']));
  break;
}
echo $response->getBody();
